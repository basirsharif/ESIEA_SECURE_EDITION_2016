function load_page(page) {
	$.ajax({
		url: "ajax.php?load=" + page,
		type: "GET",
		success: function(html) {
			window.document.getElementById('contact').innerHTML = html;
		}
	});
}

load_page('contact');
//load_page('admin');
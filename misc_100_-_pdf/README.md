# Misc 100 - PDF

L’énoncé du challenge nous indique que le fichier PDF pourrait appartenir à des terroristes et que son
contenu est important. 
On commence donc par l’analyser avec PDFiD


> swissky@crashlab:~/Bureau/make-pdf_V0_1_6$ python pdfid.py Challenge.pdf
PDFiD 0.2.1 Challenge.pdf
PDF Header: %PDF-1.1
obj
endobj
/JavaScript
7
7
1



On remarque la présence d’un objet de type Javascript, on l’exporte avec pdf-parser

> swissky@crashlab:~/Bureau/ESE
search=javascript Challenge.pdf
Forensic
1/Outils
Resolution$
python
pdf-parser.py
/JS ( /*01110110011000010111001000100000011000010011110100100111011001010111* /)
....
/*01111011101110011001101110010010111110100000000100111001110110010000001110110011000010
111001000100000011001010011110100100111011101000101111100110011010000010100110101111101*
/)


Des 0 et 1 apparaissent dans des commentaires JS, on les concatène pour obtenir un binary string.

>011101100110000101110010001000000110000100111101001001110110010101110011001001110011101
[...] 10011010000010100110101111101


Cela nous donne un code Javascript, on l’execute dans une console JS.


```js
var a='es';
var b='e{E1f';
var c='f3l_T'; 
var d='ow3r_@'; 
var e='t_3AM}
console.log(a+b+c+d+e);
```



Le résultat nous donne le flag : **ese{E1ff3l_Tow3r_@t_3AM}**
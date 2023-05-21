=== Validar identidad CF7 ===
Contributors: David Sancho
Tags: dni, cif, nie, nif, contact, contact form, form, validar, validate, identidad
Requires at least: 5.8
Tested up to: 5.8
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Valida campos de DNI, NIF, NIE y CIF utilizando el plugin Contact Form 7

== Description ==

Plugin que te permitirá incluir un campo de texto en tu formulario de Contact Form 7 para validar campos de DNI, NIF, NIE y CIF.

<strong>Funcionamiento:</strong>

Si el usuario que rellena el formulario no introduce un DNI, NIF, NIE o CIF válido, al pulsar sobre el botón "Enviar" el sistema le devolverá un error de validación.

Los números de DNI, NIF, NIE o CIF deben introducirse sin guiones ni espacios y las letras pueden estar en mayúsculas o en minúsculas: 

- Algunos ejemplos válidos: 123456789A || 123456789a  || A123456789  || a123456789 || ab123456789 


- Algunos ejemplos no válidos: 123456789-A || 123456789 a || a-123456789

<strong>Cómo crear un campo de validación en Contact Form 7:</strong>

Para poder introducir en Contact Form 7 un campo para validar un DNI, NIF, NIE o CIF, es necesario utilizar una de las siguientes etiquetas:

- Si quiere que el campo sea obligatorio: [text* identidad-nombre]

- Si no quiere que el campo sea obligatorio: [text identidad-nombre]


== Installation ==

1. Asegurese que ha instalado y activado previamente el plugin Contact Form 7
2. Descarga el archivo .zip del plugin
3. Inicia sesión en tudominio.com/wp-admin
4. Haz clic en Pluginx -> Añadir nuevo -> Subir
5. Activa el plugin

== Frequently Asked Questions ==


<strong>¿Qué debo hacer para crear un campo de validación en Contact Form 7?</strong>

Para poder introducir en Contact Form 7 un campo para validar un DNI, NIF, NIE o CIF, es necesario utilizar una de las siguientes etiquetas:

- Si quiere que el campo sea obligatorio: [text* identidad-nombre]

- Si no quiere que el campo sea obligatorio: [text identidad-nombre]

<strong>¿Funciona con documentos de identidad de otros países?</strong>

No, sólo valida documentos españoles (DNI, NIE, NIF y CIF).

<strong>¿Cómo debe escribir el usuario su DNI, NIE, NIF o CIF?</strong>

Sin guiones ni espacios. Da igual si la letra o letras están en mayúsculas o minúsculas.
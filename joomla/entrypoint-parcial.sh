#!/bin/bash
# Envoltorio del entrypoint oficial de Joomla.
# El entrypoint oficial solo instala Joomla si el comando empieza por "apache2",
# por eso se le pasa "apache2-parcial": instala Joomla y luego ejecuta ese script,
# que publica el contenido de portada y arranca Apache.
set -e
install -m 755 /parcial/apache2-parcial /usr/local/bin/apache2-parcial
exec /entrypoint.sh apache2-parcial

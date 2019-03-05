# FIXME: ver cómo hacer esto solamente la primer vez q se ejecuta
#Restaurar la BBDD
mysql -u votacionesuser -pSECRET votacionespa < pavotaciones_latest.sql
#Crear el usuario de solo lectura
mysql -u root -p -e "CREATE USER 'publico'@'%' IDENTIFIED BY 'SECRET';"
mysql -u root -p -e "GRANT SELECT ON votacionespa.* TO 'publico'@'%'"

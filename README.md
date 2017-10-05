# Proyecto GLPI Degasa.
 
 
 
-----27-07-17-----

    Se añadió el proyecto base que se encuentra en laboratorio.
      Plugins:
        FusionInventory
        DashBoard.
        Administrador de Objetos.
     
     Se añadió la Base de Datos con datos de prueba.
     
Pasos para la instalación de GLPI con sus complementos.

Programas necesarios para el correcto funcionamiento del programa.

    -fusioninventory-agent_windows-x64_2.3.20 (Programa para el cliente)
    -xampp-win32-5.6.31-0-VC11-installer
    -glpi-9.1.6
    
Plugins para glpi.

    -glpi-datainjection-2.4.2
    -glpi-genericobject-2.4.0
    -dashboard
    -fusioninventory
    
Todo los programas se encuentran en mi nube personal. Igualmente, se encuentra un manual de GLPI en español por las dudas.               https://drive.google.com/open?id=0B7VznoUZvr_ib1FUcXpQSzVuU1U
    
    
Instalación de GLPI en un área local.

   -Instalar xampp-win32-5.6.31-0-VC11-installer en la ruta C:/xampp
        *Permitir acceso de Apache a la red, saltará un mensaje.*
    Debemos dirigirnos a xampp-control y habilitar Apache y MySQL con el botón Start.
    
   -Para entrar al panel de administración de MySQL pondremos la siguiente ruta en nuestro navegador:
        http://localhost/phpmyadmin
    Si todo sale bien, nos abrirá el panel de MySQL.
    
    
   -Copiar la carpeta de GLPI en la siguiente ruta:
   C:\xampp\htdocs
   
   -Luego de eso, debemos irnos al navegador y entrar a la siguiente ruta:
        http://localhost/glpi
    Nos saldrá un panel de instalación de GLPI, aceptamos las condiciones. Luego de eso, nos preguntará dónde deseamos alojar nuestra       base de datos, en el caso de que no tengamos una, crearemos una, asignandole un nombre en el campo de texto.
    Nos mostrará nuestras credenciales iniciales y podremos ingresar ya a GLPI.
        *Nota*
        *User: glpi
        pass: glpi*
    
   -Para instalar los plugins debes pegar las carpetas a la siguiente ruta:
    C:\xampp\htdocs\glpi\plugins.
    Ingresamos a glpi con nuestras credenciales.
    Nos situamos en la fila de Configuración e ingresaremos a complementos.
    Nos mostrará todo los plugins que tengamos.
    Para habilitarlos, debemos instalarlos y luego activar.
    Tenemos todo listo para usar GLPI.
    
Instalación de FunsionInventory.
   
fusioninventory-agent_windows funciona anexo con fusioninventory (plugin de glpi).
Para su correcta instalación debes otorgarle permisos de administrador, pulsando botón derecho sobre el programa.
Seleccionamos el lenguaje preferido y daremos siguiente.
Nos saldrá el mensaje de bienvenida, daremos siguiente.
Aceptas los acuerdos de licencias y presionas siguiente.
Tipo de instalación por defecto y siguente.
Dejamos el directorio de instalación por defecto y siguiente.
Nos dirigimos al campo de Destinos Remotos.
    
        Ésta parte es importante, debes ingresar la siguiente URL en el campo de texto.
        http://*tu_direcciónip*/plugins/fusioninventory/
        
        Si no conoces tu dirección ip, debes ir al simbolo del sistema y poner el comando ipconfig, tu ip será 
        la que aparece al lado de Dirección IPV4.
        
        *Para abrir el simbolo del sistema, debes ir al botón de busqueda y escribir cmd, saltará una pantalla negra.*
        
        Un ejemplo sería:
        
        http://198.0.0.1/plugins/fusioninventory/
        
        *Nota* 
        *Copiar y pegar el enlace, porque más adelante se utilizará para la configuración del programa al GLPI.
        *
        
Pulsas siguiente.
    
Luego de eso, dejar por defecto todo, ponemos siguiente a toda las pantallas hasta llegar a Options Diverses.
    Dejamos en check, las primeras dos opciones solamente y pulsamos siguiente.
    
Siguiente hasta que inicie la instalación y finalizamos .
   


Para la configuración del cliente, debemos dirigirnos en GLPI a la fila Administración y luego en Entidades.

Nos aparecerá nuestra Entidad de SU (Super usuario)

Pinchamos en nuestra única entidad y pinchamos dónde dice FusionInventory

En servicio URL debemos pegar la URL que anteriormente copiamos y guardamos cambios.

Para inventariar, un equipo debemos ir al navegador de nuestro computador cliente e insertar la URL

    http://*tu_direcciónip*:62354/

    Ejemplo:
    
    http://198.0.0.1:6235/
    

Nos salta la página de fusion inventory y pulsamos Force an Inventory. Si nos salta OK, el computador, estará inventariado.
Eso podemos verlo en el GLPI, situandonos en inventario y seleccionando la pestaña de computadoras.


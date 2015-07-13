<?
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
		header('Content-type: text/html; charset=utf-8');
//		header('Content-type: text/html; charset=windows-1251');
		ini_set('display_errors','On');
?>
<html>

<head>
  <title>imgul</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<style>
body	{
			font-size:8pt;
			font-family: tahoma, "Trebuchet MS", arial;
		}
input	{
			border: 1px solid #e0e0e0;
			padding: 2px;
			height: 22px;
			font-family: tahoma, "Trebuchet MS", arial;
			font-size: 11px;
		}
</style>
</head>

<body>
<center>
<big><strong>IM</strong><font color=silver>A</font><b>G</b><font color=silver>E</font>
<b>U</b><font color=silver>P</font><b>L</b><font color=silver>0AD</font></big><br/>
<small>сервис обмена картинками</small>
<br/><br/><br/><br/>
<br/><strong>JPEG</strong>: Максимальный размер загружаемого файла 1мб.
<br/><strong>GIF</strong>: Максимальнай размер загружаемого файла 100кб, разрешение 100х100 пикселей.
<br/><strong>*.*</strong>: Другие форматы пока не поддерживаются.
<br/>JPEG изображение масштабируется и уменьшается до 800х600 пикселей.
<br/>GIF изображения не модифицируются.
<form enctype="multipart/form-data" action="/i/" method="post">
 <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
 <br/>	<input name="userfile" type="file" />
 <input type="submit" value="Загрузить" />
</form><?
$uploaddir = '/usr/local/customers/webs/simbul73/i/tmp/';
$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
if	(move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile))
	{
   	echo "<br/><br/>Последний раз Вы загружали: <strong>".basename($_FILES['userfile']['name'])."</strong><br/>";
	if	(filesize($uploadfile)>1048576)
		{
			echo "<br/>ОШИБКА: <font color=red>Вы пытались загрузить слишком большой файл!</font>";
			unlink($uploadfile);
		}
	else
		{
			$fstring = md5(file_get_contents($uploadfile)); 					// получили уникальное имя
			rename($uploadfile, "i/".$fstring);									// засунули файлик в папку картинок
			if (file_exists("i/".$fstring.".gif"))
			{unlink("i/".$fstring);
			 echo "<br/><img src=\"i/".$fstring.".gif\"/>";
			 echo "<br/><br/>BBCode (для форумов):<br/>
        		<input type=text size=60 onMouseOver='this.select()'
        		value='[img]http://simbul.ru/i/i/".$fstring.".gif][/img]'>
        		<br/>Прямой адрес картинки:<br/>
        		<input type=text size=60 onMouseOver='this.select()'
        		value='http://simbul.ru/i/i/".$fstring.".gif'>";
			}	            // ^^ если гифка есть - сказали об этом и показали
			elseif (file_exists("i/".$fstring."_i.jpg"))
			{unlink("i/".$fstring);
			 echo "<br/>
			 	<a href=i/".$fstring."_i.jpg><img src=i/".$fstring."_t.jpg></a>
        		<br/><br/>BBCode превью с ссылкой на оригинал:<br/><input type=text size=60 onMouseOver='this.select()'
        		value='[url=http://simbul.ru/i/i/".$fstring."_i.jpg][img]http://simbul.ru/i/".$fstring."_t.jpg[/img][/url]'>
        		<br/>Прямой адрес картинки:<br/>
        		<input type=text size=60 onMouseOver='this.select()'
        		value='http://simbul.ru/i/i/".$fstring."_i.jpg'>";
			}			// если жпг есть сказали об этом
			else																// такого не загружали
			{
				if	(exif_imagetype("i/".$fstring) == IMAGETYPE_GIF)				// типа гифка
				{
					rename ("i/".$fstring, "i/".$fstring.".gif");				// засунули в папку с картинками
					$filename = "i/".$fstring.".gif";
					list($width_orig, $height_orig) = getimagesize($filename);	// узнали разрешение картинки
					if (($width_orig>100)or
						($height_orig>100)or
						(filesize($filename)>102400)
					   )
						{	echo	"<br/><strong>ОШИБКА:</strong><dd><font color=red>Разрешение .GIF изображений <strong>не более</strong>
									100 х 100 пикселей и вес файла <strong>не может превышать</strong> 100 килобайт.<br/>Вы пытались загрузить .GIF-карт
									инку размером <strong>".$width_orig."x".$height_orig."</strong> пикселей и весом <strong>".ceil((filesize($filename))/1000)."</strong> килобайт.</font></dd>";
							unlink ("i/".$fstring.".gif");
						}
					else
					{
						echo "<br/><br/>BBCode (для форумов):<br/>
        					<input type=text size=60 onMouseOver='this.select()'
							value='[img]http://simbul.ru/i/i/".$fstring.".gif[/img]'>
							<br/>Прямой адрес картинки:<br/>
							<input type=text size=60 onMouseOver='this.select()'
							value='http://simbul.ru/i/i/".$fstring.".gif'>";
					}
				//unlink ("i/".$fstring);
    			}
				elseif	(exif_imagetype("i/".$fstring) == IMAGETYPE_JPEG)
				{
					rename ("i/".$fstring, "i/".$fstring."_i.jpg");
					//
					// converter JPEG pics starts
					//
				//	echo $fstring;
					$filename = "i/".$fstring."_i.jpg";
				//		echo $filename;
					$width = 800;
					$height = 600;
					list($width_orig, $height_orig) = getimagesize($filename);
					$ratio_orig = $width_orig/$height_orig;
					if   ($width/$height > $ratio_orig)
						 {$width = $height*$ratio_orig;}
					else {$height = $width/$ratio_orig;}

					$image_p = imagecreatetruecolor($width, $height);
					$image = imagecreatefromjpeg($filename);

					if (($width_orig > $width) && ($height_orig > $height))											 // если исходное изображение больше превьюшки то
					{imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);}  // либо уменьшаем
					else {$image_p = $image;}                                                                        // либо нихуя не делаем
					imagejpeg($image_p, "i/".$fstring."_i.jpg", 80); 												 // и высираем превьюшку
					imagedestroy($image_p);
					// echo "<img src=i/".$fstring."_i.jpg>\n";
				echo "<br/>
			 	<a href=i/".$fstring."_i.jpg><img src=i/".$fstring."_t.jpg></a>
        		<br/><br/>BBCode превью с ссылкой на оригинал:<br/><input type=text size=60 onMouseOver='this.select()'
        		value='[url=http://simbul.ru/i/i/".$fstring."_i.jpg][img]http://simbul.ru/i/i/".$fstring."_t.jpg[/img][/url]'>
        		<br/>Прямой адрес картинки:<br/>
        		<input type=text size=60 onMouseOver='this.select()'
        		value='http://simbul.ru/i/i/".$fstring."_i.jpg'>";

					//
					// converter JPEG pics ends
					//
					//
					// converter JPEG thumbs starts
					//
						$filename = "i/".$fstring."_i.jpg";
						$width = 200;
						$height = 150;
						list($width_orig, $height_orig) = getimagesize($filename);
						$ratio_orig = $width_orig/$height_orig;
						if   ($width/$height > $ratio_orig)
							 {$width = $height*$ratio_orig;}
						else {$height = $width/$ratio_orig;}

						$image_p = imagecreatetruecolor($width, $height);
						$image = imagecreatefromjpeg($filename);

						if (($width_orig > $width) && ($height_orig > $height))											 // если исходное изображение больше превьюшки то
						{imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);}  // либо уменьшаем
						else {$image_p = $image;}                                                                        // либо нихуя не делаем
						imagejpeg($image_p, "i/".$fstring."_t.jpg", 50); 												 // и высираем превьюшку
						imagedestroy($image_p);
				//		echo "<img src=i/".$fstring."_t.jpg>\n";
					//
					// converter JPEG thumbs ends
					//
				//unlink ("i/".$fstring);
				}
				else
				{echo "<br/>ОШИБКА: <font color=red>Данный тип файлов сейчас не поддерживается!";
				}
			if (file_exists("i/".$fstring)) {unlink ("i/".$fstring); die;}
			}
		}
	}
	else
	{
//die;
	}
?>
<br/><br/><br/><br/><small>.nuke &copy; 2009</small>
</center>
</body>

</html>
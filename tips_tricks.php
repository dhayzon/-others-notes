<?php

//encontrar la  primera letra de una cadena 
				$nombre=$profile['member_name'];
                $letter=$nombre[0];
//color aleatorio 
                $colores = array('blue', 'bluegrey', 'brown','green','lightgrey','purple','red','yellow','yellowgreen');
                shuffle($colores);
				$randanchor = array_rand($colores,1);
				//'.$letter.'_'. $colores[$randanchor].'
				//echo $colores[$randanchor];

<?php

$dir = "/var/www/kedo/applications/Todpole/Web/icon";

foreach(glob($dir."/*") as $path)
{
    if(is_dir($path))
    { 
       foreach(glob($path."/*") as $path2)
       {
           if(is_dir($path2) && !preg_match('/thumbnail/', $path2))
           {
               echo "danger path : ".$path2."\n";
           }
           if(is_file($path2))
           {
              if(!getimagesize($path2))
              {
                 echo $path2."\n";
                 unlink($path2);
              }
           }
           elseif(is_dir($path2))
           {
               foreach(glob($path2."/*") as $path3)
               {
                  if(is_dir($path3))
                  {
                      echo "danger dir: ". $path3;
                  }
                  elseif(is_file($path3))
                  {
                     if(!getimagesize($path3))
                     {
                        echo "not img :".$path3."\n";
                     }
                  }
               }
           }
       }
    }
    else
    {
        echo "not path: ".$path."\n";
    }

}

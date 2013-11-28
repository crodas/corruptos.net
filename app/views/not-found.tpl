@extends('layout')

@section('header')
 <!-- Loading Main Style -->
    <style type="text/css">
       .linea {width:300px; height: 20px !important; left: auto; right: auto; height: auto;}

       .linea .pixel {background-color: white; width: 20px; height: 20px; float: left}

           .pixel.piel {background-color: rgba(230, 203, 203, 0.72);}
           .pixel.piel.br, .pixel.esp_1.br {border-radius: 35px 0 0;}
           .pixel.piel.br_2 {border-radius: 0 0 0 35px;}
           .pixel.piel.br_3 {border-radius: 0 0 35px 0;}

           .pixel.masc {background-color: rgba(68, 67, 82, 0.87)}
           .pixel.masc.br {border-radius: 35px 0 0 0;}
           .pixel.cam {background-color: white}
           .pixel.pant {background-color: rgba(80, 68, 44, 1)}
           .pixel.am {background-color: rgba(213, 171, 87, 1)}
           .pixel.mang {background-color: rgba(26, 14, 1, 1)}

           .pixel.esp_1 {background-color: rgba(173, 173, 179, 0.81)}
           .pixel.esp_2 {background-color: rgba(151, 151, 160, 0.81)}

           .pixel.sangr {background-color: rgba(218, 41, 26, 0.81);}
    </style>

    <h2>Error 404: "Corrupto" no encontrado</h2>
    <small>Porque no todos son corruptos</small>


    <div class="12u">
        <div class="4u">
            &nbsp;
        </div>
        <div class="4u">

      <div class="linea">

<!--1-->  <div class="pixel"></div>
<!--2-->  <div class="pixel"></div>
<!--3-->  <div class="pixel"></div>
<!--4-->  <div class="pixel"></div>
<!--5-->  <div class="pixel"></div>
<!--6-->  <div class="pixel"></div>
<!--7-->  <div class="pixel"></div>
<!--8-->  <div class="pixel"></div>
<!--9-->  <div class="pixel"></div>
<!--10-->  <div class="pixel masc"></div>
<!--11-->  <div class="pixel"></div>
<!--12-->  <div class="pixel"></div>
<!--13-->  <div class="pixel"></div>
</div> <!-- Linea primera -->
<div class="linea">
<!--1-->  <div class="pixel"></div>
<!--2-->  <div class="pixel"></div>
<!--3-->  <div class="pixel"></div>
<!--4-->  <div class="pixel"></div>
<!--5-->  <div class="pixel"></div>
<!--6-->  <div class="pixel"></div>
<!--7-->  <div class="pixel"></div>
<!--8-->  <div class="pixel"></div>
<!--9-->  <div class="pixel masc"></div>
<!--10-->  <div class="pixel masc"></div>
<!--11-->  <div class="pixel masc"></div>
<!--12-->  <div class="pixel"></div>
<!--13-->  <div class="pixel"></div>
</div> <!-- Linea segunda -->
<div class="linea">
<!--1-->  <div class="pixel"></div>
<!--2-->  <div class="pixel"></div>
<!--3-->  <div class="pixel"></div>
<!--4-->  <div class="pixel"></div>
<!--5-->  <div class="pixel"></div>
<!--6-->  <div class="pixel"></div>
<!--7-->  <div class="pixel"></div>
<!--8-->  <div class="pixel"></div>
<!--9-->  <div class="pixel masc"></div>
<!--10-->  <div class="pixel masc"></div>
<!--11-->  <div class="pixel"></div>
<!--12-->  <div class="pixel"></div>
<!--13-->  <div class="pixel"></div>
</div> <!-- Linea tercera -->
<div class="linea">
<!--1-->  <div class="pixel"></div>
<!--2-->  <div class="pixel"></div>
<!--3-->  <div class="pixel"></div>
<!--4-->  <div class="pixel"></div>
<!--5-->  <div class="pixel"></div>
<!--6-->  <div class="pixel"></div>
<!--7-->  <div class="pixel"></div>
<!--8-->  <div class="pixel masc"></div>
<!--9-->  <div class="pixel masc"></div>
<!--10-->  <div class="pixel masc"></div>
<!--11-->  <div class="pixel masc"></div>
<!--12-->  <div class="pixel"></div>
<!--13-->  <div class="pixel"></div>
</div> <!-- Linea cuarta -->
<div class="linea">
<!--1-->  <div class="pixel esp_1 br"></div>
<!--2-->  <div class="pixel esp_2"></div>
<!--3-->  <div class="pixel"></div>
<!--4-->  <div class="pixel"></div>
<!--5-->  <div class="pixel"></div>
<!--6-->  <div class="pixel"></div>
<!--7-->  <div class="pixel"></div>
<!--8-->  <div class="pixel esp_1"></div>
<!--9-->  <div class="pixel masc"></div>
<!--10-->  <div class="pixel esp_1"></div>
<!--11-->  <div class="pixel masc"></div>
<!--12-->  <div class="pixel"></div>
<!--13-->  <div class="pixel"></div>
</div> <!-- Linea quinta -->
<div class="linea">
<!--1-->  <div class="pixel sangr"></div>
<!--2-->  <div class="pixel esp_1"></div>
<!--3-->  <div class="pixel"></div>
<!--4-->  <div class="pixel"></div>
<!--5-->  <div class="pixel"></div>
<!--6-->  <div class="pixel"></div>
<!--7-->  <div class="pixel masc br"></div>
<!--8-->  <div class="pixel masc"></div>
<!--9-->  <div class="pixel esp_1"></div>
<!--10-->  <div class="pixel masc"></div>
<!--11-->  <div class="pixel masc"></div>
<!--12-->  <div class="pixel masc"></div>
<!--13-->  <div class="pixel piel"></div>
</div> <!-- Linea sexta -->
<div class="linea">
<!--1-->  <div class="pixel"></div>
<!--2-->  <div class="pixel esp_1"></div>
<!--3-->  <div class="pixel"></div>
<!--4-->  <div class="pixel"></div>
<!--5-->  <div class="pixel"></div>
<!--6-->  <div class="pixel"></div>
<!--7-->  <div class="pixel piel"></div>
<!--8-->  <div class="pixel masc"></div>
<!--9-->  <div class="pixel masc"></div>
<!--10-->  <div class="pixel masc"></div>
<!--11-->  <div class="pixel"></div>
<!--12-->  <div class="pixel piel br"></div>
<!--13-->  <div class="pixel piel"></div>
</div> <!-- Linea septima -->
<div class="linea">
<!--1-->  <div class="pixel"></div>
<!--2-->  <div class="pixel sangr"></div>
<!--3-->  <div class="pixel esp_2"></div>
<!--4-->  <div class="pixel"></div>
<!--5-->  <div class="pixel"></div>
<!--6-->  <div class="pixel"></div>
<!--7-->  <div class="pixel piel br_2"></div>
<!--8-->  <div class="pixel"></div>
<!--9-->  <div class="pixel masc"></div>
<!--10-->  <div class="pixel"></div>
<!--11-->  <div class="pixel"></div>
<!--12-->  <div class="pixel piel"></div>
<!--13-->  <div class="pixel piel"></div>
</div> <!-- Linea octava -->
<div class="linea">
<!--1-->  <div class="pixel"></div>
<!--2-->  <div class="pixel"></div>
<!--3-->  <div class="pixel esp_1"></div>
<!--4-->  <div class="pixel"></div>
<!--5-->  <div class="pixel"></div>
<!--6-->  <div class="pixel"></div>
<!--7-->  <div class="pixel piel br"></div>
<!--8-->  <div class="pixel"></div>
<!--9-->  <div class="pixel"></div>
<!--10-->  <div class="pixel"></div>
<!--11-->  <div class="pixel"></div>
<!--12-->  <div class="pixel piel br_2"></div>
<!--13-->  <div class="pixel piel"></div>
</div> <!-- Linea octava -->
<div class="linea">
<!--1-->  <div class="pixel"></div>
<!--2-->  <div class="pixel"></div>
<!--3-->  <div class="pixel"></div>
<!--4-->  <div class="pixel esp_1"></div>
<!--5-->  <div class="pixel"></div>
<!--6-->  <div class="pixel"></div>
<!--7-->  <div class="pixel piel"></div>
<!--8-->  <div class="pixel"></div>
<!--9-->  <div class="pixel"></div>
<!--10-->  <div class="pixel"></div>
<!--11-->  <div class="pixel"></div>
<!--12-->  <div class="pixel piel br"></div>
<!--13-->  <div class="pixel piel"></div>
</div> <!-- Linea novena -->
<div class="linea">
<!--1-->  <div class="pixel"></div>
<!--2-->  <div class="pixel"></div>
<!--3-->  <div class="pixel"></div>
<!--4-->  <div class="pixel"></div>
<!--5-->  <div class="pixel esp_1"></div>
<!--6-->  <div class="pixel mang"></div>
<!--7-->  <div class="pixel piel"></div>
<!--8-->  <div class="pixel"></div>
<!--9-->  <div class="pixel"></div>
<!--10-->  <div class="pixel"></div>
<!--11-->  <div class="pixel piel br_2"></div>
<!--12-->  <div class="pixel piel"></div>
<!--13-->  <div class="pixel piel"></div>
</div> <!-- Linea decima -->
<div class="linea">
<!--1-->  <div class="pixel"></div>
<!--2-->  <div class="pixel"></div>
<!--3-->  <div class="pixel"></div>
<!--4-->  <div class="pixel"></div>
<!--5-->  <div class="pixel"></div>
<!--6-->  <div class="pixel piel br_2"></div>
<!--7-->  <div class="pixel mang"></div>
<!--8-->  <div class="pixel pant"></div>
<!--9-->  <div class="pixel am"></div>
<!--10-->  <div class="pixel am"></div>
<!--11-->  <div class="pixel pant"></div>
<!--12-->  <div class="pixel piel"></div>
<!--13-->  <div class="pixel piel br_3"></div>
</div> <!-- Linea once-->
<div class="linea">
<!--1-->  <div class="pixel"></div>
<!--2-->  <div class="pixel"></div>
<!--3-->  <div class="pixel"></div>
<!--4-->  <div class="pixel"></div>
<!--5-->  <div class="pixel"></div>
<!--6-->  <div class="pixel"></div>
<!--7-->  <div class="pixel"></div>
<!--8-->  <div class="pixel pant"></div>
<!--9-->  <div class="pixel pant"></div>
<!--10-->  <div class="pixel pant"></div>
<!--11-->  <div class="pixel pant"></div>
<!--12-->  <div class="pixel pant"></div>
<!--13-->  <div class="pixel"></div>
</div> <!-- Linea doce-->
<div class="linea">
<!--1-->  <div class="pixel"></div>
<!--2-->  <div class="pixel"></div>
<!--3-->  <div class="pixel"></div>
<!--4-->  <div class="pixel"></div>
<!--5-->  <div class="pixel"></div>
<!--6-->  <div class="pixel"></div>
<!--7-->  <div class="pixel"></div>
<!--8-->  <div class="pixel pant"></div>
<!--9-->  <div class="pixel"></div>
<!--10-->  <div class="pixel"></div>
<!--11-->  <div class="pixel"></div>
<!--12-->  <div class="pixel pant"></div>
<!--13-->  <div class="pixel"></div>
</div> <!-- Linea trece-->
<div class="linea">
<!--1-->  <div class="pixel"></div>
<!--2-->  <div class="pixel"></div>
<!--3-->  <div class="pixel"></div>
<!--4-->  <div class="pixel"></div>
<!--5-->  <div class="pixel"></div>
<!--6-->  <div class="pixel"></div>
<!--7-->  <div class="pixel pant"></div>
<!--8-->  <div class="pixel pant"></div>
<!--9-->  <div class="pixel"></div>
<!--10-->  <div class="pixel pant"></div>
<!--11-->  <div class="pixel pant"></div>
<!--12-->  <div class="pixel pant"></div>
<!--13-->  <div class="pixel"></div>
</div> <!-- Linea catorce-->
</div>

    </div>
@end

@section('content')
@end

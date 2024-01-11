<?php 
 include 'index.php';

	$sentence = file_get_contents('Sentence (1).txt'); //Proses pengambilan kelas kata
    $sentence =  explode("\n", $sentence);

    $input = file_get_contents('class_kata.txt'); //Proses pengambilan kelas kata
    $pola_kata = json_decode($input, true);
    
    $input = file_get_contents('rules.txt');
    $rules = json_decode($input, true);

    $valid = 0;
    $total = count($sentence);
    
    foreach($sentence as $kalimat){

        $string_pisah = explode(" ",$kalimat);        

        // deklasrasi variabel
        $data = [];    
        $index = 0;

        foreach ($pola_kata as $pola => $frasa_kata) {        
            for ($i = $index; $i < count($string_pisah); $i++){
                $status=false;
                $kata = $string_pisah[$i];
                $k = $i+1;
                do{
                    foreach ($frasa_kata as $frasa => $kelas_kata) {
                        foreach ($kelas_kata as $kelas => $key) {
                            
                            if(array_search(strtolower($kata),array_map('strtolower', $key)) !== false){
                                $data[$i][] = $pola;

                                $data[$i][] = $frasa;
                                $data[$i][] = $kelas;

                                $data[$i][] = $kata;

                                $status=true;
                                break;
                            }
                        }
                        if($status) break;
                    }

                    if($k == count($string_pisah) || $status){
                        break;
                    }else
                    {
                        $kata = $kata." ".$string_pisah[$k++];
                    }
                }while(!$status);

                if($status){
                    $index = $index + count(explode(' ',$kata));
                    $i+=count(explode(' ',$kata))-1;
                }else{
                    break;
                }
            }
        }

        $result = [];
        // Transpose Array
        if(!empty($data)){
            $data =  array_map(null, ...$data);

            // hapus duplikat pola kalimat
            if(is_array($data[0])){
                foreach ($data[0] as $key => $value){
                    if(!in_array($value, $result))
                        $result[]=$value;
                }
            }else{
                $result[] = $data[0];
            }
        }        

        // cek apakah input valid atau tidak
        $outputValid = false;
        if(array_search($result, $rules) == true){
            $valid++;
            $outputValid = true;
        }
    }
?>
<div class="card w-50 shadow" id="evaluasi">
<h5 class="text-center bold mb-4">Evaluasi Kalimat Valid</h5>
<?php
    echo "<p class=  text-center teks> Total Kalimat Teridentifikasi Valid : ".$valid; "</p>";
    echo "<p class= text-center teks > Total Kalimat : ".$total;"</p>";
    echo "<p class=text-center teks> Akurasi : ".number_format($valid/$total*100,2)." %";"</p>";;

?>
</div>



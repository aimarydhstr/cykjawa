<?php 
include 'index.php';
// mengambil inputan
$teks = $_POST['text'];
// memecah input berdasarkan spasi
	$string_pisah = explode(" ",$teks);

	$input = file_get_contents('class_kata.txt'); //Proses pengambilan kelas kata
    $pola_kata = json_decode($input, true);

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

                            $c = 0;
                            while($i - $c > 0){
                                if(isset($data[$i-1-$c][0]) && isset($data[$i-$c][0])){
                                    if($data[$i-1-$c][0] == $data[$i-$c][0]){
                                        $c++;
                                        $data[$i][] = "";
                                    }else
                                        break;
                                }else
                                    break;
                            }
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


// deklarasi rules 
$input = file_get_contents('rules.txt');
$rules = json_decode($input, true);

// cek apakah input valid atau tidak
$outputValid = false;
if(array_search($result, $rules) == true){
	$outputValid = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/style.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <?php
        // tampil output
        if ($outputValid) { 
            echo "<p style='font-family: Poppins, sans-serif; text-align:center; font-size:20px;font-weight: 600'>Kalimat : <span style='color:#0099ff'>$teks</span></p>";?>
            
            <div class="validasi">
                <h5 style="font-family: Poppins, sans-serif; text-align:center; font-size:20px;font-weight: 600">Hasile : <span style="color:#5aa469;">Valid</span></h5>
                <?php 
                }else{ ?>
                    <h5 style="font-family: Poppins, sans-serif; text-align:center; font-size:20px;font-weight: 600">Hasile : <span style="color:#cc0000">Ora Valid</span> </h5>
                    <?php
                }?>
            </div>
</body>
</html>

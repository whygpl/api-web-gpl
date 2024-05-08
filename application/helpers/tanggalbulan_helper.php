<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

 function tglbln($tanggal)
	{
		$str=explode('-',$tanggal);
		$bulan = array(
		'01'=>'Januari',
		'02'=>'Februari',
		'03'=>'Maret',
		'04'=>'April',
		'05'=>'Mei',
		'06'=>'Juni',
		'07'=>'Juli',
		'08'=>'Agustus',
		'09'=>'September',
		'10'=>'Oktober',
		'11'=>'November',
		'12'=>'Desember'
		);
		
		return $str['2']." ".$bulan[$str['1']]." ".$str['0'];
	}
 function tgldmy($tanggal)
	{
		$str=explode('-',$tanggal);
		return $str['2']."-".$str['1']."-".$str['0'];
	}
 
 function umur($tanggal,$tgldftr)
	{	
	
		$kn=explode('-',$tgldftr);
		$str=explode('-',$tanggal);
		$seltgl = $kn['2']-$str['2'];
		$selbln = $kn['1']-$str['1'];
		$selthn = $kn['0']-$str['0'];
		if($selbln>=0 && $seltgl>=0){
			$selisih = $selthn."-".str_pad($selbln, 2, "0", STR_PAD_LEFT);
		}elseif($selbln>=0 && $seltgl<0){
			$selisih = $selthn."-".str_pad(($selbln-1), 2, "0", STR_PAD_LEFT);
		}elseif($seltgl<0 && $selbln<2 && $selbln>-2){
			$selisih = $selthn;
		}else{
			$selisih = ($selthn-1)."-".str_pad(($selbln), 2, "0", STR_PAD_LEFT);
		}
		return $selisih;
	}
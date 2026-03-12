<?php

namespace App\Enums;

enum KenyaCounty: string
{
    case MOMBASA = 'Mombasa';
    case KWALE = 'Kwale';
    case KILIFI = 'Kilifi';
    case TANA_RIVER = 'Tana River';
    case LAMU = 'Lamu';
    case TAITA_TAVETA = 'Taita-Taveta';
    case GARISSA = 'Garissa';
    case WAJIR = 'Wajir';
    case MANDERA = 'Mandera';
    case MARSABIT = 'Marsabit';
    case ISIOLO = 'Isiolo';
    case MERU = 'Meru';
    case THARAKA_NITHI = 'Tharaka-Nithi';
    case EMBU = 'Embu';
    case KITUI = 'Kitui';
    case MACHAKOS = 'Machakos';
    case MAKUENI = 'Makueni';
    case NYANDARUA = 'Nyandarua';
    case NYERI = 'Nyeri';
    case KIRINYAGA = 'Kirinyaga';
    case MURANGA = 'Murang\'a';
    case KIAMBU = 'Kiambu';
    case TURKANA = 'Turkana';
    case WEST_POKOT = 'West Pokot';
    case SAMBURU = 'Samburu';
    case TRANS_NZOIA = 'Trans-Nzoia';
    case UASIN_GISHU = 'Uasin Gishu';
    case ELGEYO_MARAKWET = 'Elgeyo-Marakwet';
    case NANDI = 'Nandi';
    case BARINGO = 'Baringo';
    case LAIKIPIA = 'Laikipia';
    case NAKURU = 'Nakuru';
    case NAROK = 'Narok';
    case KAJIADO = 'Kajiado';
    case KERICHO = 'Kericho';
    case BOMET = 'Bomet';
    case KAKAMEGA = 'Kakamega';
    case VIHIGA = 'Vihiga';
    case BUNGOMA = 'Bungoma';
    case BUSIA = 'Busia';
    case SIAYA = 'Siaya';
    case KISUMU = 'Kisumu';
    case HOMA_BAY = 'Homa Bay';
    case MIGORI = 'Migori';
    case KISII = 'Kisii';
    case NYAMIRA = 'Nyamira';
    case NAIROBI = 'Nairobi';

    public static function values(): array
    {
        $values = array_column(self::cases(), 'value');
        sort($values); // Sorts A-Z
        
        return $values;
    }
}

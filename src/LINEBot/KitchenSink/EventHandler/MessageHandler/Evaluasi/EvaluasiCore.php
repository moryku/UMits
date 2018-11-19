<?php
namespace LINE\LINEBot\KitchenSink\EventHandler\MessageHandler\Evaluasi;
class EvaluasiCore {

    public static function search($data, $jawaban, $starttime) {
        $jawabanSplit = explode(" ", $jawaban);
        // for ($i = 0; $i < (sizeof($jawabanSplit)); $i++) {
        //     if (empty($jawabanSplit[$i]) !== false) {
        //         unset($jawabanSplit[$i]); 
        //     }
        // }
        // $jawabanSplit = array_values($jawabanSplit);
        $nodeAnswer;
        for ($i = 1; $i < (sizeof($jawabanSplit)); $i++) {
            $nodeAnswer = self::searchBFS($data, $jawabanSplit[$i]);
            // return $nodeAnswer;
            if ($nodeAnswer != null && $nodeAnswer["status"] == false) {
                $diff = microtime(true) - $starttime;
                $sec = intval($diff);
                $micro = $diff - $sec;
                $final = "0".strftime(str_replace('0.', '.', sprintf('%.10f', $micro))." detik");
                return $nodeAnswer["message"]." (".$final.")";
                break;
            } else {
                $data = $nodeAnswer["child"];
            }
        }
        if ($i == (sizeof($jawabanSplit)-1)) {
            if ($data != null && sizeof($data) > 0) {
                $nodeAnswer = searchBFS($data, null);
                if ($nodeAnswer["status"] == false) {
                    $diff = microtime(true) - $starttime;
                    $sec = intval($diff);
                    $micro = $diff - $sec;
                    $final = "0".strftime(str_replace('0.', '.', sprintf('%.10f', $micro))." detik");
                    return $nodeAnswer["message"]." (".$final.")";
                }
            }
        }
        $diff = microtime(true) - $starttime;
        $sec = intval($diff);
        $micro = $diff - $sec;
        $final = "0".strftime(str_replace('0.', '.', sprintf('%.10f', $micro))." detik");
        return "Jawaban Anda Benar"." (".$final.")";
    }

    public static function searchBFS($data, $targetValue) {
        for ($i = 0; $i < (sizeof($data)); $i++) {
            // var_dump($data[$i]["value"]);
            // var_dump($targetValue);
            // return $data[$i]["value"]."---".$targetValue;
            if ($data[$i]["value"] == $targetValue) {
                if ($data[$i]["status"] == true) {
                    if (self::hasChild($data[$i]) == true) {
                        return $data[$i];
                    } else {
                        return null;
                    }
                } else {
                    return $data[$i];
                }
            } else if ($data[$i]["value"] == null) {
                return $data[$i];
            }
        }
    }

    public static function hasChild($data) {
        if (isset($data["child"]) && sizeof($data["child"]) > 0 ) {
            return true;
        }
        return false;
    }
}
<?php
namespace LINE\LINEBot\KitchenSink\EventHandler\MessageHandler\Evaluasi;
class EvaluasiCore {

    public static function search($data, $jawaban) {
        $jawabanSplit = explode(" ", $jawaban);

        return $jawabanSplit[0]."---".$jawabanSplit[1];
        $nodeAnswer;
        for ($i = 0; $i < (sizeof($jawabanSplit)); $i++) {
            $nodeAnswer = self::searchBFS($data, $jawabanSplit[$i]);
            return $nodeAnswer;
            if ($nodeAnswer != null && $nodeAnswer["status"] == false) {
                return $nodeAnswer["message"];
                break;
            } else {
                $data = $nodeAnswer["child"];
            }
        }
        if ($i == (sizeof($jawabanSplit)-1)) {
            if ($data != null && sizeof($data) > 0) {
                $nodeAnswer = searchBFS($data, null);
                if ($nodeAnswer["status"] == false) {
                    return $nodeAnswer["message"];
                }
            }
        }
        return "Jawaban Anda Benar";
    }

    public static function searchBFS($data, $targetValue) {
        for ($i = 0; $i < (sizeof($data)); $i++) {
            // var_dump($data[$i]["value"]);
            // var_dump($targetValue);
            return $data[$i]["value"]."---".$targetValue;
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
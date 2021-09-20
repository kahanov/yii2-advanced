<?php

namespace common\helpers;

use Yii;
use DateTime;

class BaseCommonHelper
{

    /**
     * @param array $variants
     * @param int $number
     * @return string
     */
    public static function getPlural(array $variants, int $number): string
    {
        // Формула для образования множественного числа.
        $pluralForm = $number % 10 == 1 && $number % 100 != 11 ? 0 : ($number % 10 >= 2 && $number % 10 <= 4 && ($number % 100 < 10 || $number % 100 >= 20) ? 1 : 2);
        return $variants[$pluralForm];
    }

    /**
     * @param string $string
     * @return string
     */
    public static function slugify(string $string): string
    {
        $string = trim($string);
        $insert = mb_strtolower($string);    // Если работаем с юникодными строками
        $replase = [
            // Буквы
            'а' => 'a', 'б' => 'b', 'в' => 'v',
            'г' => 'g', 'д' => 'd', 'е' => 'e',
            'ё' => 'e', 'ж' => 'zh', 'з' => 'z',
            'и' => 'i', 'й' => 'y', 'к' => 'k',
            'л' => 'l', 'м' => 'm', 'н' => 'n',
            'о' => 'o', 'п' => 'p', 'р' => 'r',
            'с' => 's', 'т' => 't', 'у' => 'u',
            'ф' => 'f', 'х' => 'h', 'ц' => 'c',
            'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch',
            'ь' => '', 'ы' => 'y', 'ъ' => '',
            'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
            //Удаляем
            ' - ' => '',
            '_' => '',
            '.' => '',
            ':' => '',
            ';' => '',
            ',' => '',
            '!' => '',
            '?' => '',
            '>' => '',
            '<' => '',
            '&' => '',
            '*' => '',
            '%' => '',
            '$' => '',
            '"' => '',
            '\'' => '',
            '(' => '',
            ')' => '',
            '`' => '',
            '+' => '',
            '/' => '',
            '\\' => '',
            '«' => '',
            '»' => '',
            '@' => '',
            '^' => '',
            '=' => '',
            '|' => '',
            '{' => '',
            '}' => '',
            '[' => '',
            ']' => '',
            '~' => '',
            '±' => '',
            '#' => '',
            '№' => '',
            '&nbsp;' => '',
        ];
        $insert = urldecode($insert);
        $insert = htmlspecialchars_decode($insert);
        $insert = htmlentities($insert);
        $insert = preg_replace("/  +/", " ", $insert); // Удаляем лишние пробелы
        $insert = strtr($insert, $replase); // Переводим в транслит и удаляем лишние символы
        $insert = preg_replace("/  +/", " ", $insert); // Снова удаляем лишние пробелы
        $insert = strtr($insert, [' ' => '-']); // Заменяем все пробелы на -
        $insert = preg_replace('/\s+/', '', $insert);//Удаляем все оставшиеся пробелы
        return $insert;
    }

    /**
     * @param string $date
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public static function period(string $date): string
    {
        return self::periodTime($date);
    }

    /**
     * @param string $date
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public static function periodTime(string $date): string
    {
        $variantsYear = [Yii::t('common', 'год'), Yii::t('common', 'года'), Yii::t('common', 'лет')];
        $variantsMonth = [Yii::t('common', 'месяц'), Yii::t('common', 'месяца'), Yii::t('common', 'месяцев')];
        $variantsDay = [Yii::t('common', 'день'), Yii::t('common', 'дня'), Yii::t('common', 'дней')];
        $variantsHour = [Yii::t('common', 'час'), Yii::t('common', 'часа'), Yii::t('common', 'часов')];
        $variantsMinute = [Yii::t('common', 'минуту'), Yii::t('common', 'минуты'), Yii::t('common', 'минут')];
        $date = Yii::$app->formatter->asDate($date, 'php:Y-m-d H:i:s');
        $datetime = new DateTime($date);
        $interval = $datetime->diff(new DateTime(date("Y-m-d H:i:s")));
        $year = $interval->y;
        $month = $interval->m;
        $day = $interval->d;
        $hour = $interval->h;
        $minute = $interval->i;
        $period = '';
        if ($year != 0) {
            $pluralYear = self::getPlural($variantsYear, $year);
            $period .= sprintf('%d %s', $year, $pluralYear);
        }
        if ($month != 0) {
            if (!empty($period)) {
                $period .= ' ';
            }
            $pluralMonth = self::getPlural($variantsMonth, $month);
            $period .= sprintf('%d %s', $month, $pluralMonth);
        }
        if (empty($period)) {
            if ($day != 0) {
                $pluralDay = self::getPlural($variantsDay, $day);
                $period = sprintf('%d %s', $day, $pluralDay);
            } else {
                if ($hour != 0) {
                    $pluralHour = self::getPlural($variantsHour, $hour);
                    $period = sprintf('%d %s', $hour, $pluralHour);

                    $pluralMinute = self::getPlural($variantsMinute, $minute);
                    $period .= ' ' . sprintf('%d %s', $minute, $pluralMinute);
                } else {
                    $period = Yii::t('common', 'Несколько минут');
                }
            }
        }
        return $period;
    }

    /**
     * @param string $url
     * @return bool|string
     */
    public static function getUrl(string $url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.6 (KHTML, like Gecko) Chrome/16.0.897.0 Safari/535.6');
        curl_setopt($ch, CURLOPT_COOKIEFILE, '');
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * Склонение городов
     * @param string $word
     * @return array|string
     */
    public static function declension(string $word)
    {
        //Согласные
        $consonants = ["б", "в", "г", "д", "ж", "з", "й", "к", "л", "м", "н", "п", "р", "с", "т", "ф", "х", "ц", "ч", "ш", "щ", "ь"];
        //Гласные
        $vocal = ["а", "у", "о", "ы", "и", "э", "я", "ю", "ё", "е"];
        //Глухие согласные
        $deafConsonants = ["п", "ф", "к", "т", "ш", "с", "х", "ц", "ч", "щ"];
        //Звонкие согласные
        $voicedConsonants = ["б", "в", "г", "д", "ж", "з", "л", "м", "н", "р"];
        //Общие окончания
        $endings = [
            "ий", "ай", "ая", "ты", "ей", "ри", "ды", "ое", "ец", "ый", "ны", "ие", "ые", "бо", "но", "во", "ть", "ро", "ор", "ма", "ау", "ко", "кт", "ну", "ян", "ар", "нь", "ый", "лы", "ль",
            "чи", "ки", "ой", "ок", "аы", "ни"
        ];
        //Окончание при глухой согласной
        $deafEndings = ["ий" => "ом", "ой" => "ом"];
        //Окончание при звонкой согласной
        $voicedEndings = ["ий" => "ем", "ой" => "ое", "ец" => "це"];
        //Простые окончания
        $regularEndings = [
            "ай" => "ае", "ая" => "ой", "ты" => "тах", "ей" => "ее", "ри" => "рях", "ды" => "дах", "лы" => "лах", "ое" => "ом", "ор" => "ору", "ец" => "це", "ый" => "ом",
            "ны" => "нах", "ие" => "их", "ые" => "ых", "ий" => "ом", "ль" => "ле", "нь" => "не", "чи" => "чах", "ки" => "ках", "ок" => "ке", "аы" => "ау", "ни" => "нях"
        ];
        //Окончание при третьем гласном знаке
        $vocalEndings = ["ень" => "не", "оби" => "би", "лец" => "льце", "очи" => "очи"];
        //Несклоняемые окончания
        $noDeclension = ["ну", "бо", "но", "во", "ть", "ро", "ма", "ау", "ко", "кт", "ян", "ар", "ак", "ев", "ас", "ид", "он", "ыл", "сь"];
        //Проверяем сложное название или простое по наличию дефиса или пробела
        preg_match("~(-на-|-|\s)~", $word, $delimiter) ? $word = explode($delimiter[0], $word) : null;
        //Если сложно выполняем склонение для каждой части отдельно
        if (is_array($word)) {
            foreach ($word as $key => $value) {
                //Получаем последний знак
                $singleChar = mb_substr($value, -1);
                //Определяем гласная или согласная
                if (in_array($singleChar, $vocal)) {//Если гласная
                    //Получаем окончание из 2 знаков
                    $twoChars = mb_strtolower(mb_substr($value, -2));
                    if (!in_array($twoChars, $noDeclension)) {
                        if (in_array($twoChars, $endings)) {
                            //Если окончание присутствует в массиве получаем третий знак
                            $thirdChar = mb_strtolower(mb_substr($value, -3, 1));
                            //Если глухой
                            if (in_array($thirdChar, $deafConsonants)) {
                                if (array_key_exists($twoChars, $deafEndings)) {
                                    $word[$key] = mb_substr($value, 0, -2) . $deafEndings[$twoChars];
                                } else {
                                    $word[$key] = mb_substr($value, 0, -2) . $regularEndings[$twoChars];
                                }
                                //Если звонкий
                            } elseif (in_array($thirdChar, $voicedConsonants)) {
                                if (array_key_exists($twoChars, $voicedEndings)) {
                                    $word[$key] = mb_substr($value, 0, -2) . $voicedEndings[$twoChars];
                                } else {
                                    $word[$key] = mb_substr($value, 0, -2) . $regularEndings[$twoChars];
                                }
                                //Если третий символ гласный
                            } elseif (in_array($thirdChar, $vocal)) {
                                //Получаем окончание из 3 знаков
                                $threeChars = mb_substr($value, -3);
                                if (array_key_exists($threeChars, $vocalEndings)) {
                                    $word[$key] = mb_substr($value, 0, -3) . $vocalEndings[$threeChars];
                                } else {
                                    $word[$key] = mb_substr($value, 0, -2) . $regularEndings[$twoChars];
                                }
                                //Или берем стандартное окнчание
                            } else {
                                $word[$key] = mb_substr($value, 0, -2) . $regularEndings[$twoChars];
                            }
                        } else {
                            $word[$key] = mb_substr($value, 0, -1) . "е";
                        }
                    }
                }
                if (in_array($singleChar, $consonants)) {//Если согласная
                    //Получаем окончание из 2 знаков
                    $twoChars = mb_strtolower(mb_substr($value, -2));
                    if (!in_array($twoChars, $noDeclension)) {
                        if (in_array($twoChars, $endings)) {
                            //Если окончание присутствует в массиве получаем третий знак
                            $thirdChar = mb_strtolower(mb_substr($value, -3, 1));
                            if (in_array($thirdChar, $deafConsonants)) {
                                if (array_key_exists($twoChars, $deafEndings)) {
                                    $word[$key] = mb_substr($value, 0, -2) . $deafEndings[$twoChars];
                                } else {
                                    $word[$key] = mb_substr($value, 0, -2) . $regularEndings[$twoChars];
                                }
                                //Если звонкий
                            } elseif (in_array($thirdChar, $voicedConsonants)) {
                                if (array_key_exists($twoChars, $voicedEndings)) {
                                    $word[$key] = mb_substr($value, 0, -2) . $voicedEndings[$twoChars];
                                } else {
                                    $word[$key] = mb_substr($value, 0, -2) . $regularEndings[$twoChars];
                                }
                            } elseif (in_array($thirdChar, $vocal)) {
                                //Получаем окончание из 3 знаков
                                $threeChars = mb_substr($value, -3);
                                if (array_key_exists($threeChars, $vocalEndings)) {
                                    $word[$key] = mb_substr($value, 0, -3) . $vocalEndings[$threeChars];
                                } else {
                                    $word[$key] = mb_substr($value, 0, -2) . $regularEndings[$twoChars];
                                }
                            }
                        } else $word[$key] = "{$value}е";
                    }
                }

            }
            //Склеиваем обратно и возвращаем
            return implode($delimiter[0], $word);
        }
        //Если навзание простое выполняем склонение в штатном режиме
        $singleChar = mb_strtolower(mb_substr($word, -1));
        //Если гласная
        if (in_array($singleChar, $vocal)) {
            $twoChars = mb_strtolower(mb_substr($word, -2));
            if (!in_array($twoChars, $noDeclension)) {
                if (in_array($twoChars, $endings)) {
                    //Если окончание присутствует в массиве получаем третий знак
                    $threeChars = mb_substr($word, -3);
                    if (in_array($threeChars, $vocalEndings)) {
                        return mb_substr($word, 0, -3) . $vocalEndings[$threeChars];
                    }
                    return mb_substr($word, 0, -2) . $regularEndings[$twoChars];
                }
                return mb_substr($word, 0, -1) . "е";
            }
            return $word;
        }
        //Если согласная
        if (in_array($singleChar, $consonants)) {
            $twoChars = mb_strtolower(mb_substr($word, -2));
            if (!in_array($twoChars, $noDeclension)) {
                if (in_array($twoChars, $endings)) {
                    //Если окончание присутствует в массиве получаем третий знак
                    $thirdChar = mb_strtolower(mb_substr($word, -3, 1));
                    if (in_array($thirdChar, $deafConsonants)) {
                        if (array_key_exists($twoChars, $deafEndings)) {
                            return mb_substr($word, 0, -2) . $deafEndings[$twoChars];
                        } else {
                            return mb_substr($word, 0, -2) . $regularEndings[$twoChars];
                        }
                        //Если звонкий
                    } elseif (in_array($thirdChar, $voicedConsonants)) {
                        if (array_key_exists($twoChars, $voicedEndings)) {
                            return mb_substr($word, 0, -2) . $voicedEndings[$twoChars];
                        } else {
                            return mb_substr($word, 0, -2) . $regularEndings[$twoChars];
                        }
                    }
                    return mb_substr($word, 0, -2) . $regularEndings[$twoChars];
                }
            }
            return "{$word}е";
        }
    }

    /**
     * @param $str
     * @param string $encoding
     * @return false|string
     */
    public static function mb_ucfirst($str, $encoding = 'UTF-8')
    {
        $str = mb_ereg_replace('^[\ ]+', '', $str);
        $str = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding) .
            mb_substr($str, 1, mb_strlen($str), $encoding);
        return $str;
    }

    /**
     * @param $imagePath
     * @return null|string
     */
    public static function getImageMimeType($imagePath): ?string
    {
        $mimes  =[
            IMAGETYPE_GIF => 'image/gif',
            IMAGETYPE_JPEG => 'image/jpg',
            IMAGETYPE_PNG => 'image/png',
            IMAGETYPE_WEBP => 'image/webp'
        ];
        if (($imageType = exif_imagetype($imagePath)) && (array_key_exists($imageType ,$mimes))) {
            return $mimes[$imageType];
        } else {
            return false;
        }
    }
}

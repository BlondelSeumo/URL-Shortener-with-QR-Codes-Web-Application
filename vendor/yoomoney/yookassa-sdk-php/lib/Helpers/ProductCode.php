<?php

/*
 * The MIT License
 *
 * Copyright (c) 2024 "YooMoney", NBСO LLC
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace YooKassa\Helpers;

use YooKassa\Model\Receipt\MarkCodeInfo;

/**
 * Класс для формирования тега 1162 на основе кода в формате Data Matrix
 *
 * @example 04-product-code.php 6 7 Вариант через метод
 * @example 04-product-code.php 15 7 Вариант через массив
 *
 * @link https://git.yoomoney.ru/projects/SDK/repos/yookassa-sdk-php/browse/lib/Helpers/ProductCode.php
 *
 * @package YooKassa
 */
class ProductCode
{
    /** @var string Код типа маркировки DataMatrix */
    const PREFIX_DATA_MATRIX = '444D';
    /** @var string Код типа маркировки UNKNOWN */
    const PREFIX_UNKNOWN = '0000';
    /** @var string Код типа маркировки EAN_8 */
    const PREFIX_EAN_8 = '4508';
    /** @var string Код типа маркировки EAN_13 */
    const PREFIX_EAN_13 = '450D';
    /** @var string Код типа маркировки ITF_14 */
    const PREFIX_ITF_14 = '4909';
    /** @var string Код типа маркировки FUR */
    const PREFIX_FUR = '5246';
    /** @var string Код типа маркировки EGAIS_20 */
    const PREFIX_EGAIS_20 = 'C514';
    /** @var string Код типа маркировки EGAIS_30 */
    const PREFIX_EGAIS_30 = 'C51E';

    /** @var string Тип маркировки UNKNOWN */
    const TYPE_UNKNOWN = 'unknown';
    /** @var string Тип маркировки EAN_8 */
    const TYPE_EAN_8 = 'ean_8';
    /** @var string Тип маркировки EAN_13 */
    const TYPE_EAN_13 = 'ean_13';
    /** @var string Тип маркировки ITF_14 */
    const TYPE_ITF_14 = 'itf_14';
    /** @var string Тип маркировки GS_10 */
    const TYPE_GS_10 = 'gs_10';
    /** @var string Тип маркировки GS_1M */
    const TYPE_GS_1M = 'gs_1m';
    /** @var string Тип маркировки SHORT */
    const TYPE_SHORT = 'short';
    /** @var string Тип маркировки FUR */
    const TYPE_FUR = 'fur';
    /** @var string Тип маркировки EGAIS_20 */
    const TYPE_EGAIS_20 = 'egais_20';
    /** @var string Тип маркировки EGAIS_30 */
    const TYPE_EGAIS_30 = 'egais_30';

    /** @var string Идентификатор применения (идентификационный номер единицы товара) */
    const AI_GTIN = '01';
    /** @var string Идентификатор применения (серийный номер) */
    const AI_SERIAL = '21';
    /** @var string Дополнительный идентификатор применения (цена единицы измерения товара) */
    const AI_SUM = '8005';

    /** @var int Максимальная длина последовательности для кода продукта unknown */
    const MAX_PRODUCT_CODE_LENGTH = 30;
    /** @var int Максимальная длина последовательности для кода маркировки типа unknown */
    const MAX_MARK_CODE_LENGTH = 32;

    /** @var string Код типа маркировки */
    private $prefix;

    /**
     * @var string Тип маркировки
     * @example unknown
     */
    private $type;

    /**
     * @var string Global Trade Item Number
     * Глобальный номер товарной продукции в единой международной базе товаров GS1 https://ru.wikipedia.org/wiki/GS1
     * @example 04630037591316
     */
    private $gtin;

    /**
     * @var string Серийный номер товара
     * @example sgEKKPPcS25y5
     */
    private $serial;

    /**
     * @var array|null Массив дополнительных идентификаторов применения
     */
    private $appIdentifiers;

    /**
     * @var string Сформированный тег 1162. Формат: hex([prefix]+gtin+serial)
     * @example 04 36 03 BE F5 14 73  67  45  4b  4b  50  50  63  53  32  35  79  35
     */
    private $result;

    /**
     * @var MarkCodeInfo Сформированный код товара (тег в 54 ФЗ — 1163)
     */
    private $markCodeInfo;

    /** @var bool Флаг использования кода типа маркировки */
    private $usePrefix = false;

    /**
     * ProductCode constructor.
     * @param string|null $codeDataMatrix Строка, расшифрованная из QR-кода
     * @param bool|string $usePrefix Нужен ли код типа маркировки в результате
     */
    public function __construct($codeDataMatrix = null, $usePrefix = true)
    {
        $this->preparePrefix($usePrefix);

        if (!empty($codeDataMatrix) && $this->parseCodeMatrixData($codeDataMatrix)) {
            $this->result = $this->calcResult();
        }
    }

    /**
     * Возвращает код типа маркировки
     * @return string Код типа маркировки
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * Устанавливает код типа маркировки
     * @param string|int $prefix Код типа маркировки
     * @return ProductCode
     */
    public function setPrefix($prefix)
    {
        if ($prefix === null || $prefix === '') {
            $this->prefix = null;
            return $this;
        }

        if (is_int($prefix)) {
            $prefix = dechex($prefix);
        }
        $this->prefix = str_pad($prefix, 4, '0', STR_PAD_LEFT);

        return $this;
    }

    /**
     * Возвращает тип маркировки
     * @return string Тип маркировки
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Устанавливает тип маркировки
     * @param string $type Тип маркировки
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Возвращает глобальный номер товарной продукции
     * @return string Глобальный номер товарной продукции
     */
    public function getGtin()
    {
        return $this->gtin;
    }

    /**
     * Устанавливает глобальный номер товарной продукции
     * @param string $gtin Глобальный номер товарной продукции
     * @return ProductCode
     */
    public function setGtin($gtin)
    {
        if ($gtin === null || $gtin === '') {
            $this->gtin = null;
        } else {
            $this->gtin = $gtin;
        }
        return $this;
    }

    /**
     * Возвращает серийный номер товара
     * @return string Серийный номер товара
     */
    public function getSerial()
    {
        return $this->serial;
    }

    /**
     * Устанавливает серийный номер товара
     * @param string $serial Серийный номер товара
     * @return ProductCode
     */
    public function setSerial($serial)
    {
        if ($serial === null || $serial === '') {
            $this->prefix = null;
        } else {
            $this->serial = $serial;
        }
        return $this;
    }

    /**
     * Возвращает массив дополнительных идентификаторов применения
     * @return array|null Массив дополнительных идентификаторов применения
     */
    public function getAppIdentifiers()
    {
        return $this->appIdentifiers;
    }

    /**
     * Устанавливает массив дополнительных идентификаторов применения
     * @param array|null $appIdentifiers Массив дополнительных идентификаторов применения
     */
    public function setAppIdentifiers($appIdentifiers)
    {
        $this->appIdentifiers = $appIdentifiers;
    }

    /**
     * Возвращает сформированный тег 1162.
     * @return string Сформированный тег 1162.
     */
    public function getResult()
    {
        if (!$this->result) {
            $this->result = $this->calcResult();
        }

        return $this->result;
    }

    /**
     * @return MarkCodeInfo
     */
    public function getMarkCodeInfo()
    {
        return $this->markCodeInfo;
    }

    /**
     * @param MarkCodeInfo|array|string $markCodeInfo
     */
    public function setMarkCodeInfo($markCodeInfo)
    {
        if (is_array($markCodeInfo)) {
            $markCodeInfo = new MarkCodeInfo($markCodeInfo);
        }
        if (is_string($markCodeInfo)) {
            $markCodeInfo = new MarkCodeInfo(array(
                $this->getType() => $markCodeInfo
            ));
        }

        $this->markCodeInfo = $markCodeInfo;
    }

    /**
     * Возвращает флаг использования кода типа маркировки
     * @return bool
     */
    public function isUsePrefix()
    {
        return $this->usePrefix;
    }

    /**
     * Устанавливает флаг использования кода типа маркировки
     * @param bool $usePrefix Флаг использования кода типа маркировки
     * @return ProductCode
     */
    public function setUsePrefix($usePrefix)
    {
        $this->usePrefix = (bool)$usePrefix;
        return $this;
    }

    /**
     * Формирует тег 1162.
     * @return string Сформированный тег 1162.
     */
    public function calcResult()
    {
        $result = '';

        if (!$this->validate()) {
            return $result;
        }

        if ($this->isUsePrefix()) {
            $result = $this->getPrefix() ?: self::PREFIX_DATA_MATRIX;
        }

        switch ($this->getType()) {
            case self::TYPE_EAN_8:
            case self::TYPE_EAN_13:
            case self::TYPE_ITF_14:
                $result .= $this->numToHex($this->getGtin());
                break;
            case self::TYPE_FUR:
            case self::TYPE_EGAIS_20:
            case self::TYPE_EGAIS_30:
            case self::TYPE_UNKNOWN:
                $result .= $this->strToHex($this->getGtin());
                break;
            case self::TYPE_SHORT:
                $result .= $this->numToHex($this->getGtin());
                $result .= $this->strToHex($this->getSerial());
                break;
            case self::TYPE_GS_1M:
            case self::TYPE_GS_10:
                $result .= $this->numToHex($this->getGtin());
                $result .= $this->strToHex($this->getSerial());
                if ($sum = $this->getAIValue(self::AI_SUM)) {
                    $result .= $this->strToHex($sum);
                }
                break;
        }

        return $this->chunkStr($result);
    }

    /**
     * Устанавливает prefix и usePrefix в зависимости от входящего параметра
     * @param mixed $usePrefix Код типа маркировки или bool
     */
    private function preparePrefix($usePrefix)
    {
        if ($usePrefix) {
            $this->setUsePrefix(true);
            if (is_string($usePrefix) || is_int($usePrefix)) {
                $this->setPrefix($usePrefix);
            } else {
                $this->setPrefix(self::PREFIX_UNKNOWN);
            }
        } else {
            $this->setUsePrefix(false);
            $this->setPrefix(null);
        }
    }

    /**
     * Извлекает необходимые данные из строки, расшифрованной из QR-кода и устанавливает соответствующие свойства.
     * Возвращает результат в виде bool
     * @param string $codeDataMatrix Строки, расшифрованная из QR-кода
     * @return false
     */
    private function parseCodeMatrixData($codeDataMatrix)
    {
        $this->fillData(
            $this->parseScanString($codeDataMatrix)
                ?: array('type' => self::TYPE_UNKNOWN, 'code' => $codeDataMatrix)
        );

        return $this->validate();
    }

    /**
     * Проверяет заполненность необходимых свойств
     * @return bool
     */
    public function validate()
    {
        if (!$this->getType()) {
            return false;
        }

        switch ($this->getType()) {
            case self::TYPE_EAN_8:
            case self::TYPE_EAN_13:
            case self::TYPE_ITF_14:
            case self::TYPE_FUR:
            case self::TYPE_EGAIS_20:
            case self::TYPE_EGAIS_30:
            case self::TYPE_UNKNOWN:
                return $this->getGtin() !== null;
            case self::TYPE_GS_10:
            case self::TYPE_GS_1M:
            case self::TYPE_SHORT:
                return $this->getGtin() && $this->getSerial();
        }

        return false;
    }

    /**
     * Заполняет поля объекта из массива данных
     * @param array $data Массив данных
     * @return void
     */
    private function fillData($data)
    {
        $this->setType($data['type']);
        $this->setPrefix($this->getPrefixByType($data['type']));

        switch ($this->getType()) {
            case self::TYPE_EAN_8:
            case self::TYPE_EAN_13:
            case self::TYPE_ITF_14:
            case self::TYPE_FUR:
            case self::TYPE_EGAIS_30:
                $this->setGtin($data['match1']);
                $this->setMarkCodeInfo($this->getGtin());
                break;
            case self::TYPE_EGAIS_20:
                $this->setGtin($data['match2']);
                $this->setMarkCodeInfo($this->getGtin());
                break;
            case self::TYPE_SHORT:
                $this->setGtin($data['match1']);
                $this->setSerial($data['match2']);
                $this->setMarkCodeInfo(self::AI_GTIN . $this->getGtin() . self::AI_SERIAL . $this->getSerial());
                break;
            case self::TYPE_GS_1M:
            case self::TYPE_GS_10:
                $this->setGtin($data['match1']);
                if (!empty($data['split']) && count($data['split']) > 1) {
                    $this->setSerial(array_shift($data['split']));
                    $this->setAppIdentifiers($data['split']);
                } else {
                    $this->setSerial($data['match2']);
                }
                $this->setMarkCodeInfo(self::AI_GTIN . $this->getGtin() . self::AI_SERIAL . $this->getSerial());
                break;
            case self::TYPE_UNKNOWN:
                $this->setGtin(strlen($data['code']) > self::MAX_PRODUCT_CODE_LENGTH ? substr($data['code'], 0, self::MAX_PRODUCT_CODE_LENGTH) : $data['code']);
                $this->setSerial(strlen($data['code']) > self::MAX_MARK_CODE_LENGTH ? substr($data['code'], 0, self::MAX_MARK_CODE_LENGTH) : $data['code']);
                $this->setMarkCodeInfo($this->getSerial());
                break;
        }
    }

    /**
     * Возвращает массив данных, полученных по правилам из считанной сканером последовательности
     * @param string $code Считанная сканером последовательность
     * @return array|void Массив данных, полученных по правилам
     */
    private function parseScanString($code)
    {
        foreach ($this->getMarkCodeRules() as $codeType => $rule) {
            if (!empty($rule['length']) && strlen($code) !== $rule['length']) {
                continue;
            }
            preg_match($rule['pattern'], $code, $matches);
            if ($rule['matches'][1] && empty($matches[1])) {
                continue;
            }
            if ($rule['matches'][2] && empty($matches[2])) {
                continue;
            }
            if (!empty($rule['split'])) {
                $split = preg_split($rule['split'], $matches[2]);
            }
            return array(
                'type' => $codeType,
                'code' => $code,
                'rules' => $rule['matches'],
                'match1' => $rule['matches'][1] ? $matches[1] : null,
                'match2' => $rule['matches'][2] ? $matches[2] : null,
                'split' => !empty($split) ? $split : null,
            );
        }
    }

    /**
     * Возвращает список правил определения типа маркировки
     * @return array[]
     */
    private function getMarkCodeRules()
    {
        return array(
            self::TYPE_GS_1M => array(
                'pattern' => '#^01(\d{14})21(.+)((91(.+)92(.+))|(93[\w!"%&\'()*+,-./_:;=<>?]{4}(.*)))$#ui',
                'matches' => array(1 => true, 2 => true),
                'split' => '#\\\u001d|\x{001d}#ui',
            ),
            self::TYPE_GS_10 => array(
                'pattern' => '#^01(\d{14})21(.+)$#ui',
                'matches' => array(1 => true, 2 => true),
                'split' => '#\\\u001d|\x{001d}#ui',
            ),
            self::TYPE_SHORT => array(
                'length' => 29,
                'pattern' => '#^(\d{14})(.+)$#i',
                'matches' => array(1 => true, 2 => true),
            ),
            self::TYPE_EGAIS_20 => array(
                'length' => 68,
                'pattern' => '#^(.{8})(.{23})(.+)$#ui',
                'matches' => array(1 => false, 2 => true),
            ),
            self::TYPE_EGAIS_30 => array(
                'length' => 150,
                'pattern' => '#^(.{14})(.+)$#ui',
                'matches' => array(1 => true, 2 => false),
            ),
            self::TYPE_ITF_14 => array(
                'length' => 14,
                'pattern' => '#^(0\d{13})$#ui',
                'matches' => array(1 => true, 2 => false),
            ),
            self::TYPE_EAN_13 => array(
                'length' => 13,
                'pattern' => '#^(\d{13})$#ui',
                'matches' => array(1 => true, 2 => false),
            ),
            self::TYPE_EAN_8 => array(
                'length' => 8,
                'pattern' => '#^(\d{8})$#ui',
                'matches' => array(1 => true, 2 => false),
            ),
            self::TYPE_FUR => array(
                'length' => 20,
                'pattern' => '#^((\w{2})-(\d{6})-(\w{10}))$#ui',
                'matches' => array(1 => true, 2 => false),
            ),
        );
    }

    /**
     * Возвращает значение идентификатора применения, если он присутствует
     * @param string $appIdentifier Идентификатор применения
     * @return string|null Значение идентификатора применения
     */
    private function getAIValue($appIdentifier)
    {
        if (!$this->getAppIdentifiers()) {
            return null;
        }
        foreach ($this->getAppIdentifiers() as $item) {
            if (strpos($item, $appIdentifier) === 0) {
                return str_replace($appIdentifier, '', $item);
            }
        }
        return null;
    }

    /**
     * Возвращает префикс кода товара типу маркировки
     * @param string|null $type Тип маркировки
     * @return string|null Префикс кода товара
     */
    private function getPrefixByType($type = null)
    {
        if (!$type) {
            $type = $this->getType();
        }
        $map = array(
            self::TYPE_UNKNOWN  => self::PREFIX_UNKNOWN,
            self::TYPE_EAN_8    => self::PREFIX_EAN_8,
            self::TYPE_EAN_13   => self::PREFIX_EAN_13,
            self::TYPE_ITF_14   => self::PREFIX_ITF_14,
            self::TYPE_GS_10    => self::PREFIX_DATA_MATRIX,
            self::TYPE_GS_1M    => self::PREFIX_DATA_MATRIX,
            self::TYPE_SHORT    => self::PREFIX_DATA_MATRIX,
            self::TYPE_FUR      => self::PREFIX_FUR,
            self::TYPE_EGAIS_20 => self::PREFIX_EGAIS_20,
            self::TYPE_EGAIS_30 => self::PREFIX_EGAIS_30,
        );
        return !empty($map[$type]) ? $map[$type] : self::PREFIX_UNKNOWN;
    }

    /**
     * Разбивает пробелами строку на пары символов и переводит в верхний регистр
     * @param string $string Подготовленная к разбиению строка
     * @return string
     */
    private function chunkStr($string)
    {
        return strtoupper(trim(chunk_split($string, 2, ' ')));
    }

    /**
     * Переводит десятичное число в шестнадцатеричный вид и дополняет нулями до 12 символов слева
     * @param string $string Входящее число (Глобальный номер товарной продукции)
     * @return string
     */
    private function numToHex($string)
    {
        return str_pad($this->baseConvert($string), 12, '0', STR_PAD_LEFT);
    }

    /**
     * Переводит число из одной системы исчисления в другую
     * Замена dechex() для 32-битных версии PHP
     *
     * @param string $numString
     * @param int $fromBase
     * @param int $toBase
     * @return string
     */
    private function baseConvert($numString, $fromBase = 10, $toBase = 16)
    {
        $chars = "0123456789abcdefghijklmnopqrstuvwxyz";
        $toString = substr($chars, 0, $toBase);

        $length = mb_strlen($numString);
        $result = '';
        $number = array();

        for ($i = 0; $i < $length; $i++) {
            $number[$i] = strpos($chars, substr($numString, $i, 1));
        }

        do {
            $divide = 0;
            $newLen = 0;
            for ($i = 0; $i < $length; $i++) {
                $divide = $divide * $fromBase + $number[$i];
                if ($divide >= $toBase) {
                    $number[$newLen++] = (int)($divide / $toBase);
                    $divide %= $toBase;
                } elseif ($newLen > 0) {
                    $number[$newLen++] = 0;
                }
            }
            $length = $newLen;
            $result = substr($toString, $divide, 1) . $result;
        } while ($newLen != 0);

        return $result;
    }

    /**
     * Переводит строку в шестнадцатеричный вид
     * @param string $string Входящая строка (Серийный номер товара)
     * @return string
     */
    private function strToHex($string)
    {
        $hex = '';
        $length = mb_strlen($string);
        for ($i = 0; $i < $length; $i++) {
            $ord = ord($string[$i]);
            $hexCode = dechex($ord);
            $hex .= substr('0' . $hexCode, -2);
        }
        return $hex;
    }

    /**
     * Переводит строку из шестнадцатеричного вида в обычный
     * Нужен для тестирования
     * @param string $hex Входящая строка в шестнадцатеричном виде
     * @return string
     */
    private function hexToStr($hex)
    {
        $string = '';
        for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
            $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        }
        return $string;
    }

    /**
     * Приводит объект к строке
     * @return string
     */
    public function __toString()
    {
        return $this->getResult();
    }
}

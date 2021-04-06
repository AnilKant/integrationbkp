<?php

/**
 *  Created by Amit Shukla.
 *  User: cedcoss
 *  Date: 23/4/19 11:51 AM
 *
 */

namespace frontend\components;

use yii\base\Component;

class DateTime extends Component
{
    protected $dateTimeObj;
    public $timezone;
    public $format;
    public $time = 'NOW';
    public $timestamp;
    protected $dateTimeFormat;
    
    /**
     * initiate timezone and format
     * @throws \Exception
     */
    public function init()
    {
        $this->dateTimeObj = new \DateTime($this->time, new \DateTimeZone($this->timezone));
    }
    
    /**
     * get date time
     * @return mixed
     */
    public function getDateTime()
    {
        if(is_null($this->dateTimeObj)) {
            $this->init();
        }
        
        return $this->dateTimeObj->format($this->getFormat());
    }
    
    /**
     * get time stamp
     * @return mixed
     */
    public function getTimeStamp()
    {
        if (is_null($this->dateTimeObj)) {
            $this->init();
        }
        return $this->dateTimeObj->getTimestamp();
    }
    
    /**
     * @return mixed
     */
    public function getDateTimeObj()
    {
        if (is_null($this->dateTimeObj)) {
            $this->init();
        }
        return $this->dateTimeObj;
    }
    
    /**
     * Get Time
     *
     * @return String
     */
    public function getTime()
    {
        return $this->time;
    }
    
    /**
     * Get TimeZone
     *
     * @return String
     */
    public function getTimeZone()
    {
        return $this->timezone;
    }
    
    /**
     * Get Time Format
     *
     * @return String
     */
    public function getFormat()
    {
        return $this->format;
    }
    
    /**
     * set timestamp
     * @param $timestamp
     * @return $this
     */
    public function setTimestamp($timestamp)
    {
        if(is_numeric($timestamp))
        {
            $this->dateTimeObj = null;
            $this->timestamp = $timestamp;
            $this->init();
            $this->dateTimeObj->setTimestamp($this->timestamp);
        }
        return $this;
    }
    
    /**
     * Set Time
     *
     * @return DateTime
     * @var String $time Example : 2019-05-02 12:07:57
     */
    public function setTime($time)
    {
        if (!empty($time))
        {
            $this->time = $time;
            $this->dateTimeObj = null;
        }
        return $this;
    }
    
    /**
     * Set TimeZone
     *
     * @return DateTime
     * @var String $timezone Example : Asia/Calcutta
     */
    public function setTimeZone($timezone)
    {
        if (!empty($timezone))
        {
            $this->timezone = $timezone;
            $this->dateTimeObj = null;
            $this->init();
        }
        return $this;
    }
    
    /**
     * Set Time Format
     *
     * @return DateTime
     * @var String $format Example : Y-m-d H:i:s
     */
    public function setFormat($format)
    {
        if (!empty($format))
        {
            $this->format = $format;
            $this->dateTimeObj = null;
            $this->init();
            $this->dateTimeObj->format($this->format);
        }
        return $this;
    }
    
}
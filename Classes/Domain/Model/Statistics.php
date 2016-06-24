<?php
namespace PITS\PitsSiteStatistics\Domain\Model;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016 Ricky Mathew <ricky.mk@pitsolutions.com>, Pit Solutions
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Statistics
 */
class Statistics extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * serviceAccountEmail
     *
     * @var string
     * @validate NotEmpty
     * @validate EmailAddress
     */
    protected $serviceAccountEmail = '';

    /**
     * keyFileLocation
     *
     * @var string
     * @validate NotEmpty
     * @validate PITS\PitsSiteStatistics\Validation\Validator\KeyFileValidator
     */
    protected $keyFileLocation = '';

    /**
     * defaultAccount
     * @var int
     */
    protected $defaultAccount = 0;

    /**
     * webTitle
     *@validate NotEmpty
     * @validate PITS\PitsSiteStatistics\Validation\Validator\WebTitleValidator
     * @var string
     */
    protected $webTitle = '';

    /**
     * propertyTrackingid
     *@validate NotEmpty
     * @var string
     */
    protected $propertyTrackingid = '';

    /**
     * StatisticsRepository
     *
     * @var PITS\PitsSiteStatistics\Domain\Repository\StatisticsRepository
     * @inject
     */
    protected $statisticsRepository;

    /**
     * Returns the serviceAccountEmail
     *
     * @return string $serviceAccountEmail
     */
    public function getServiceAccountEmail()
    {
        return $this->serviceAccountEmail;
    }

    /**
     * Sets the serviceAccountEmail
     *
     * @param string $serviceAccountEmail
     * @return void
     */
    public function setServiceAccountEmail($serviceAccountEmail)
    {
        $this->serviceAccountEmail = $serviceAccountEmail;
    }

    /**
     * Returns the keyFileLocation
     *
     * @return string $keyFileLocation
     */
    public function getKeyFileLocation()
    {
        return $this->keyFileLocation;
    }

    /**
     * Sets the keyFileLocation
     *
     * @param string $keyFileLocation
     * @return void
     */
    public function setKeyFileLocation($keyFileLocation)
    {
        $this->keyFileLocation = $keyFileLocation;
    }

    /**
     * Returns the propertyTrackingid
     * @return string $propertyTrackingid
     */
    public function getPropertyTrackingid()
    {
        return $this->propertyTrackingid;
    }

    /**
     * Sets the propertyTrackingid
     * @param string $propertyTrackingid
     * @return void
     */
    public function setPropertyTrackingid($propertyTrackingid)
    {
        $this->propertyTrackingid = $propertyTrackingid;
    }

    /**
     * Returns the defaultAccount field vlue
     *
     * @return int $defaultAccount
     */
    public function getDefaultAccount()
    {
        return $this->defaultAccount;
    }

    /**
     * Sets the defaultAccount
     *
     * @param int $defaultAccount
     * @return void
     */
    public function setDefaultAccount($defaultAccount)
    {
        $this->defaultAccount = $defaultAccount;
    }

    /**
     * Returns the webTitle
     *
     * @return string $webTitle
     */
    public function getWebTitle()
    {
        return $this->webTitle;
    }

    /**
     * Sets the webTitle
     *
     * @param string $websiteTitle
     * @return void
     */
    public function setWebTitle($webTitle)
    {
        $this->webTitle = $webTitle;
    }

}

<?php
namespace PITS\PitsSiteStatistics\Library;

require_once PATH_site . 'typo3conf/ext/pits_site_statistics/Libraries/vendor/autoload.php';
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
 * AnalyticsLibrary
 */
class AnalyticsLibrary
{

    /**
     * StatisticsModel
     * @var PITS\PitsSiteStatistics\Domain\Model\Statistics
     * @inject
     */
    protected $StatisticsModel;

    /**
     * serviceAccountEmail
     * @var string
     */
    public $serviceAccountEmail;

    /**
     * keyFileLocation
     * @var string
     */
    public $keyFileLocation;

    /**
     * client
     */
    public $client;

    /**
     * analytics
     */
    public $analytics;

    /**
     * Get the client and analytic sevices
     * @param type $serviceAccountEmail
     * @param type $keyFileLocation
     * @return type
     */
    public function getAnalyticsAccount($serviceAccountEmail, $keyFileLocation)
    {
        $analyticHandler           = [];
        $this->serviceAccountEmail = $serviceAccountEmail;
        $this->keyFileLocation     = PATH_site . $keyFileLocation;
        $this->client              = new \Google_Client();
        $this->client->setApplicationName("HelloAnalytics");
        $this->analytics = new \Google_Service_Analytics($this->client);
        if (file_exists($this->keyFileLocation)) {
            $key = file_get_contents($this->keyFileLocation);
        } else {
            throw new \Exception('Key file not found.');
        }
        $cred = new \Google_Auth_AssertionCredentials(
            $this->serviceAccountEmail,
            array(\Google_Service_Analytics::ANALYTICS_READONLY),
            $key
        );
        $this->client->setAssertionCredentials($cred);
        if ($this->client->getAuth()->isAccessTokenExpired()) {
            $this->client->getAuth()->refreshTokenWithAssertion($cred);
        }
        $analyticHandler['analytics'] = $this->analytics;
        $analyticHandler['client']    = $this->client;
        return $analyticHandler;
    }

    /**
     * Get the analytic property with the $trackingId passed
     * @param type &$analytics
     * @param type $trackingId
     * @return type
     */
    public function getFirstprofileId(&$analytics, $trackingId)
    {
        // Get the user's first view (profile) ID.
        $property_tracking_id = $trackingId;
        // Get the list of accounts for the authorized user.
        $accounts = $analytics->management_accounts->listManagementAccounts();

        if (count($accounts->getItems()) > 0) {
            $items          = $accounts->getItems();
            $firstAccountId = $items[0]->getId();

            // Get the list of properties for the authorized user.
            $properties = $analytics->management_webproperties
                ->listManagementWebproperties($firstAccountId);

            if (count($properties->getItems()) > 0) {
                $firstPropertyId = '';
                $items           = $properties->getItems();
                foreach ($items as $analyticproperty) {
                    if ($analyticproperty['id'] == $property_tracking_id) {
                        $firstPropertyId = $analyticproperty->getId();
                    }
                }
                if ($firstPropertyId == '') {
                    throw new \Exception('No google analytic properties found which matches your account\'s Tracking ID.');
                }
                // Get the list of views (profiles) for the authorized user.
                $profiles = $analytics->management_profiles
                    ->listManagementProfiles($firstAccountId, $firstPropertyId);
                if (count($profiles->getItems()) > 0) {
                    $items = $profiles->getItems();

                    // Return the first view (profile) ID.
                    return $items[0]->getId();

                } else {
                    throw new \Exception('No google analytic views (profiles) found for this user.');
                }
            } else {
                throw new \Exception('No google analytic properties found for this user.');
            }
        } else {
            throw new \Exception('No google analytic account linked with this user.');
        }
    }

    /**
     * Get the analytics data as per the parameters passed
     * @param type &$analytics
     * @param type $profileId
     * @param type $startdate
     * @param type $enddate
     * @param type $metrics
     * @param type $dimension
     * @return type
     */
    public function getResults(&$analytics, $profileId, $startdate, $enddate, $metrics, $dimension)
    {
        // Calls the Core Reporting API
        $now        = new \DateTime();
        $end_date   = $now->format('Y-m-d');
        $start_date = $now->modify('-1 month')->format('Y-m-d');

        try {
            return $analytics->data_ga->get(
                'ga:' . $profileId,
                $startdate,
                $enddate,
                $metrics,
                array(
                    'dimensions'  => $dimension,
                    'sort'        => $metrics,
                    'max-results' => 20,
                )
            );
        } catch (\Exception $e) {
            return '';
        }
    }

/**
 * Description
 * @param type array $rows
 * @return type array
 */
    public function drawDonutChart($rows, $metrics, $dimension)
    {
        $data = array();
        if (is_array($rows)) {
            foreach ($rows as $row) {
                if ($row[0] != '(not set)') {
                    $data[] = array(
                        'label' => $row[0],
                        'value' => $row[1],
                    );
                }
            }
        }
        return $data;
    }

    /**
     * Description
     * @param type  array $rows
     * @return type array
     */
    public function drawBarChart($rows, $metrics, $dimension)
    {
        $metricsParameter   = str_replace('ga:', '', $metrics);
        $dimensionParameter = str_replace('ga:', '', $dimension);
        $data               = array();
        if (is_array($rows)) {
            foreach ($rows as $row) {
                if ($row[0] != '(not set)') {
                    $data[] = array(
                        $dimensionParameter => $row[0],
                        $metricsParameter   => $row[1],
                    );
                }
            }
        }

        return $data;
    }

    /**
     * Description
     * @param type $rows
     * @return type array
     */
    public function drawLineChart($rows, $metrics, $dimension)
    {
        $metricsParameter   = str_replace('ga:', '', $metrics);
        $dimensionParameter = str_replace('ga:', '', $dimension);
        $data               = array();
        if (is_array($rows)) {
            foreach ($rows as $row) {
                if ($row[0] != '(not set)') {
                    $data[] = array(
                        $dimensionParameter => $row[0],
                        $metricsParameter   => $row[1],
                    );
                }
            }
        }
        return $data;
    }

    /**
     * Get the dimesion and metric parameters
     * @param type $client
     * @return type
     */
    public function metadata($client)
    {

        $gcurl    = new \Google_IO_Curl($client);
        $response = $gcurl->makeRequest(
            new \Google_Http_Request("https://www.googleapis.com/analytics/v3/metadata/ga/columns")
        );

        //verify returned data
        $data = json_decode($response->getResponseBody());

        $items           = $data->items;
        $data_items      = [];
        $dimensions_data = [];
        $metrics_data    = [];

        foreach ($items as $item) {
            if ($item->attributes->status == 'DEPRECATED') {
                continue;
            }

            if ($item->attributes->type == 'DIMENSION') {
                $dimensions_data[$item->attributes->group][] = $item;
            }

            if ($item->attributes->type == 'METRIC') {
                $metrics_data[$item->attributes->group][] = $item;
            }

        } //foreach

        $data_items['dimensions'] = $dimensions_data;
        $data_items['metrics']    = $metrics_data;

        return $data_items;

    }

    /**
     * Formats the metic parameter name
     * @param type $metrics
     * @return type
     */
    public function getMetricParamter($metrics)
    {
        $metricsParameter = str_replace('ga:', '', $metrics);
        return $metricsParameter;
    }

    /**
     * Formats the dimension parameter name
     * @param type $dimension
     * @return type
     */
    public function getDimensionParamter($dimension)
    {
        $dimensionParameter = str_replace('ga:', '', $dimension);
        return $dimensionParameter;
    }
}

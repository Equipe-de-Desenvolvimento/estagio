<?php
// defined('BASEPATH') OR exit('No direct script access allowed');

class Googlecalendar_model extends Model
{

    public function __construct()
    {

        parent::__construct();
        // $this->load->library('session');
        // $this->load->library('googleplus');
        
        $this->calendar = new Google_Service_Calendar($this->googleplus->client());

    }


    public function isLogin()
    {


        $token = $this->session
            ->userdata('google_calendar_access_token');

        if ($token) {

            $this->googleplus
                ->client
                ->setAccessToken($token);

        }

        if ($this->googleplus->isAccessTokenExpired()) {

            return false;

        }

        return $token;

    }


    public function loginUrl()
    {

        return $this->googleplus
            ->loginUrl();

    }


    public function login($code)
    {

        $login = $this->googleplus
            ->client
            ->authenticate($code);


        if ($login) {
            $token = $this->googleplus
                ->client
                ->getAccessToken();

            $this->session
                ->set_userdata('google_calendar_access_token', $token);

            $this->session->set_userdata('google', $token);

            return true;

        }

    }


    public function getUserInfo()
    {

        return $this->googleplus->getUser();

    }


    public function getEvents($calendarId = 'primary', $timeMin = false, $timeMax = false, $maxResults = 10, $paciente = null)
    {


        if ( ! $timeMin) {

            $timeMin = date("c", strtotime(date('Y-m-d ').' 00:00:00'));

        } else {

            $timeMin = date("c", strtotime($timeMin));

        }


        if ( ! $timeMax) {

            $timeMax = date("c", strtotime(date('Y-m-d ').' 23:59:59'));

        } else {

            $timeMax = date("c", strtotime($timeMax));

        }



        $optParams = array(
            'maxResults'   => $maxResults,
            'orderBy'      => 'startTime',
            'singleEvents' => true,
            'timeMin'      => $timeMin,
            'timeMax'      => $timeMax,
            'timeZone'     => 'America/Fortaleza',
            // 'summary'      => $paciente,

        );

        $token_teste = json_decode($this->session->userdata('google_calendar_access_token'));

        $results = $this->googlecalendar->calendar->events->listEvents($calendarId, $optParams, $token_teste);

        $data = array();

        foreach ($results->getItems() as $item) {

            $start = date('d-m-Y H:i', strtotime($item->getStart()->dateTime));

            array_push(

                $data,
                array(

                    'id'          => $item->getId(),
                    'summary'     => $item->getSummary(),
                    'description' => $item->getDescription(),
                    'creator'     => $item->getCreator(),
                    'start'       => $item->getStart()->dateTime,
                    'end'         => $item->getEnd()->dateTime,


                )

            );

        }

        return $data;

    }

    public function DeletarAgenda($calendarId = 'primary', $id){
        $token_teste = json_decode($this->session->userdata('google_calendar_access_token'));
        return $this->calendar->events->delete($calendarId, $id, $array = array(), $token_teste);
    }

    public function addEvent($calendarId = 'primary', $data, $qtdeemail)
    {

        if($qtdeemail == 2){
            $event = new Google_Service_Calendar_Event(
                array(
                    'summary'     => $data['summary'],
                    'description' => $data['description'],
                    'start'       => array(
                        'dateTime' => $data['start'],
                        'timeZone' => 'America/Fortaleza',
                    ),
                    'end'         => array(
                        'dateTime' => $data['end'],
                        'timeZone' => 'America/Fortaleza',
                    ),
                    'attendees'   => array(
                        array('email' => $data['email'], 'responseStatus' => 'accepted', 'colorId' => $data['colorid']),
                        array('email' => $data['email2'], 'responseStatus' => 'accepted', 'colorId' => $data['colorid']),
                    ),
                    'location' => $data['localizacao'],
                    'colorId' => $data['colorid'],
                )
            );
        }else{
            $event = new Google_Service_Calendar_Event(
                array(
                    'summary'     => $data['summary'],
                    'description' => $data['description'],
                    'start'       => array(
                        'dateTime' => $data['start'],
                        'timeZone' => 'America/Fortaleza',
                    ),
                    'end'         => array(
                        'dateTime' => $data['end'],
                        'timeZone' => 'America/Fortaleza',
                    ),
                    'attendees'   => array(
                        array('email' => $data['email'], 'responseStatus' => 'accepted', 'colorId' => $data['colorid']),
                    ),
                    'location' => $data['localizacao'],
                    'colorId' => $data['colorid'],
                )
            );
        }


        $array = array();
        $token_teste = json_decode($this->session->userdata('google_calendar_access_token'));
       // $token = '{"access_token":"ya29.a0AfH6SMCYcXidY0NBCzYHARAUHs70goaRtLGEut2BXm6rSL6ezxlSQFIVKUz2ClynPtwCTblk4N_V_T2bV9zXjL3OXtHOZEmBieKvWvhAA8i4zp4rzmhY49akqVpU1HBaFI7AQlJvdCNjmDtBF35GVymyQjT0VK1gLgo","expires_in":3599,"scope":"https:\/\/www.googleapis.com\/auth\/userinfo.profile https:\/\/www.googleapis.com\/auth\/calendar","token_type":"Bearer","id_token":"eyJhbGciOiJSUzI1NiIsImtpZCI6IjVlZmZhNzZlZjMzZWNiNWUzNDZiZDUxMmQ3ZDg5YjMwZTQ3ZDhlOTgiLCJ0eXAiOiJKV1QifQ.eyJpc3MiOiJhY2NvdW50cy5nb29nbGUuY29tIiwiYXpwIjoiNDUzMTI1Njg5MjA1LXFkbzVzNjI1NmZ2b2NxbXJuaG1sZW03bmIxZjhvYm9nLmFwcHMuZ29vZ2xldXNlcmNvbnRlbnQuY29tIiwiYXVkIjoiNDUzMTI1Njg5MjA1LXFkbzVzNjI1NmZ2b2NxbXJuaG1sZW03bmIxZjhvYm9nLmFwcHMuZ29vZ2xldXNlcmNvbnRlbnQuY29tIiwic3ViIjoiMTExMzA3MzQwMjQ5NDM1MTg4NDc5IiwiYXRfaGFzaCI6ImdFS2hId1JRaVpPbk41QTRiNmtwOEEiLCJpYXQiOjE2MDE2NjA0NTksImV4cCI6MTYwMTY2NDA1OX0.hWnSGo-Nf6igvcst5uYilq2OmS7tBxh1lfNO2CKOiSx5ze8htMy-UvFCtKidA6TxE18YZkPvyiUyhDX2Z5LtcZg8f7btncW5-HdTG9ShisymJS7LvxX3FSofIG-trdkzGi9UgWpOHVLI357iU0XlcIc93WTN5ic3JV3ejcrrknxHyJYuaneHj_yMZinEs-0A9k5Gp_7BGCzvwo9JgE7kW-VlhlAJPdzv7B7ZjFKyLGEnuMXfQB7EloQJMVPMt-Ns8ndTuVF16Vn76pYoGyreHx2X02tiBo1BBljZl_aELPOQKPMmllT2X2OdRA5dKme5skG1O8-DIQDmr_Q02eZwsA","created":1601660459}';
        //$token_teste = json_decode($token);

        // echo '<pre>';
        // print_r($token_teste);
        // die;

        return $this->calendar->events->insert($calendarId, $event, $array, $token_teste);


    }


}
<?php

return [
    'app.salutation'    => 'Best Regards',
    'app.greeting'      => 'Hello!',
    'app.actionurl'     =>  'If you are having trouble clicking the ":actionText" button, copy and paste this URL into your web browser:',

    'regionadmin.greeting'  => 'Hello Admin!',
    'user.greeting'  => 'Hello :username!',

    'newuser.subject'   =>  'Approve New User Registration',
    'newuser.line'      =>  'A new user has registered with email :email. Please validate and approve or reject.',
    'newuser.action'    =>  'Validate New User',

    'welcome.subject' => 'Welcome to '.config('app.name'),
    'welcome.line1' => 'You are entitled to work as :userroles for :region. ',
    'welcome.line2' => 'You will find important news after each login at the home page. If yo uclikc on the logo in the top left corner you can get to the home screen anytime again. ',
    'welcome.line3' => 'You are allowed to modify data of these clubs and leagues: <ul><li> :clubs </li><li> :leagues </li></ul> Data of all other clubs and leagues you can find in the briefing dashboards.',

    'verifyemail.subject'   =>  'Verify Your eMail Address',
    'verifyemail.action'    =>  'Verify eMail',
    'verifyemail.line1'     =>  'Please click the button below to verify your email address.',
    'verifyemail.line2'     =>  'If you did not create an account, no further action is required.',

    'resetpassword.subject' =>  'Reset Password',
    'resetpassword.action'  =>  'Reset Password',
    'resetpassword.line1'   =>  'You are receiving this email because we received a password reset request for your account.',
    'resetpassword.line2'   =>  'This password reset link will expire in :count minutes.',
    'resetpassword.line3'   =>  'If you did not request a password reset, no further action is required.',

    'rejectuser.subject'    => 'Access Request Rejected',
    'rejectuser.line1'  =>  'The administrator of region :REGION has rejected your access request with the following reason ":reason".',
    'rejectuser.line2'  =>  'In case of any questions pls email to :email',

    'approveuser.subject'   => 'Access Request Approved',
    'approveuser.line1' =>  'The administrator of region :REGION has approved your access request.',
    'approveuser.line2' =>  'Make sure to verify your email and happy days with DUnkomatic !',

    'newseason.subject' =>  'New Season Started',
    'newseason.line1'   =>  'The new season :season has been kicked off in DunkOmatic.',
    'newseason.line2'   =>  'Some work and fun is ahead of you.',
    'newseason.line3'   =>  'Stay tuned and watch your message board or inbox !',

    'checkregionsetting.subject' => 'New Season has Started',
    'checkregionsetting.line1' => 'The new season :season has been kicked off in DunkOmatic.',
    'checkregionsetting.line2' => 'All dates in the settings of your region :REGION have been auto-adjusted and moved on year ahead.',
    'checkregionsetting.line3' => 'Please verify these dates and adjust accoprdingly.',
    'checkregionsetting.action' => 'Regionsettings',

    'missinglead.subject'   =>  'Missing League- or CLubleads',
    'missinglead.line1' =>  'Clubs without any admin:',
    'missinglead.line2' =>  'Leagues without any admin:',

    'leaguerptavail.subject'    =>  ':LEAGUE reports are available !',
    'leaguerptavail.line'   =>  'The game reports for league :league have been generated and are ready for you to download.',
    'leaguerptavail.action'     =>  'Download Reports',

    'leaguegamesgen.subject'    => ':LEAGUE games generated',
    'leaguegamesgen.line1'    => 'The games for league :league have been generated and are ready for you ',
    'leaguegamesgen.line2'    => 'to check or edit your home game dates and start times.',
    'leaguegamesgen.action'    => 'Edit Homegames',
    'leaguegamesgen.action2'    => 'Homegames Overview',

    'clubrptavail.subject'    =>  ':CLUB reports are available !',
    'clubrptavail.line'   =>  'The game reports for club :club have been generated and are ready for you to download.',
    'clubrptavail.action'     =>  'Download Reports',

    'invalidemail.subject'  =>  'Invalid eMail Adresses :CLUBCODE',
    'invalidemail.line'  =>  'The following members of club :clubname have been registered with an invalid eMail address.',
    'invalidemail.action'  =>  'Update eMail Adresses',

    'registerteams.subject'  =>  'Team Registration for League :LEAGUE',
    'registerteams.line1'  =>  'Your club has been assigned to league :league.',
    'registerteams.line2'  =>  'You are ready to register a team with the league now.',
    'registerteams.action'  =>  'Register Team',
    'selectleagueno.subject'  =>  'Pick Team numbers for League :LEAGUE',
    'selectleagueno.line1'    =>  'All teams for League :league have been registered.',
    'selectleagueno.line2'    =>  'Please pick a league number for your teams now.',
    'selectleagueno.action'   =>  'League Number Selection',

    'league.salutation'  =>  'Best Regards, Your league admin :leaguelead',

    'clubdeassigned.subject'  =>  ':LEAGUE Team Removed',
    'clubdeassigned.line1'  =>  'Your team :TEAM has been removed from league :LEAGUE.',
    'clubdeassigned.line2'  =>  'In case of any questions please check with the league admin.',

    'charpickenabled.subject1'  =>  ':REGION League Character Ballot Season :season :mode',
    'charpickenabled.line1' =>  'The region admin :region has :mode thie ballot to pick team characters for the season :season.',
    'charpickenabled.line2' =>  'To pick the characters for your teams click the button below',
    'charpickenabled.open'  =>  'opened',
    'charpickenabled.closed'    =>  'closed',
    'charpickenabled.action'    =>  'Char Picking',

    'inviteuser.subject'    => 'DunkoMatic User Invitation',
    'inviteuser.action' =>  'User Registration',
    'inviteuser.line1'  =>  ':sendername has invited you as user of DunkOMatic.',
    'inviteuser.line2'  =>  'Click the button below to get to the registration page.',

    'overlappinggames.subject'  => 'Overlapping Home Games',
    'overlappinggames.line1'  => 'We found :games_count overlapping home games scheduled for your club :CLUB.',
    'overlappinggames.line2'  => 'When you click on column ":overlapcolumn", you can see the overlapping games highlighted and may now modify those.',
    'overlappinggames.action'  => 'Modify Game Start',

    'unscheduledgames.subject'  => 'Home Games Scheduling',
    'unscheduledgames.line1'  => 'We found :games_count unscheduled home games for your club :CLUB.',
    'unscheduledgames.line2'  => 'When you click on column ":gaemtimecolumn", you can see the games with no start time and may now modify those.',
    'unscheduledgames.action'  => 'Enter Game Start Time',

    'event.char.picked' => ':LEAGUE: :CLUB has picked Number :LEAGUE_NO',
    'event.char.released' => ':LEAGUE: :CLUB has released Number :LEAGUE_NO',
];

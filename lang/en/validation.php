<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'The :attribute must be accepted.',
    'active_url' => ':attribute is not a valid URL.',
    'after' => 'Date :attribute must be after :date.',
    'after_or_equal' => 'The :attribute must be a date after or equal to :date.',
    'alpha' => ':attribute can only contain characters.',
    'alpha_dash' => 'The :attribute may only contain letters, numbers, dashes and underscores.',
    'alpha_num' => 'The :attribute may only contain letters and numbers.',
    'array' => 'The :attribute must be an array.',
    'before' => 'The :attribute must be a date before :date.',
    'before_or_equal' => 'The :attribute must be a date before or equal to :date.',
    'between' => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file' => 'The :attribute must be between :min and :max kilobytes.',
        'string' => 'The :attribute must be between :min and :max characters.',
        'array' => 'The :attribute must have between :min and :max items.',
    ],
    'boolean' => 'The :attribute field must be true or false.',
    'confirmed' => 'The :attribute confirmation does not match.',
    'date' => 'The :attribute is not a valid date.',
    'date_equals' => 'The :attribute must be a date equal to :date.',
    'date_format' => 'The :attribute does not match the format :format.',
    'different' => 'The :attribute and :other must be different.',
    'digits' => 'The :attribute must be :digits digits.',
    'digits_between' => 'The :attribute must be between :min and :max digits.',
    'dimensions' => 'The :attribute has invalid image dimensions.',
    'distinct' => 'The :attribute field has a duplicate value.',
    'email' => 'The :attribute must be a valid email address.',
    'ends_with' => 'The :attribute must end with one of the following: :values.',
    'exists' => 'The selected :attribute is invalid.',
    'file' => 'The :attribute must be a file.',
    'filled' => 'The :attribute field must have a value.',
    'gt' => [
        'numeric' => 'The :attribute must be greater than :value.',
        'file' => 'The :attribute must be greater than :value kilobytes.',
        'string' => 'The :attribute must be greater than :value characters.',
        'array' => 'The :attribute must have more than :value items.',
    ],
    'gte' => [
        'numeric' => 'The :attribute must be greater than or equal :value.',
        'file' => 'The :attribute must be greater than or equal :value kilobytes.',
        'string' => 'The :attribute must be greater than or equal :value characters.',
        'array' => 'The :attribute must have :value items or more.',
    ],
    'image' => 'The :attribute must be an image.',
    'in' => 'The selected :attribute is invalid.',
    'in_array' => 'The :attribute field does not exist in :other.',
    'integer' => 'The :attribute must be an integer.',
    'ip' => 'The :attribute must be a valid IP address.',
    'ipv4' => 'The :attribute must be a valid IPv4 address.',
    'ipv6' => 'The :attribute must be a valid IPv6 address.',
    'json' => 'The :attribute must be a valid JSON string.',
    'lt' => [
        'numeric' => 'The :attribute must be less than :value.',
        'file' => 'The :attribute must be less than :value kilobytes.',
        'string' => 'The :attribute must be less than :value characters.',
        'array' => 'The :attribute must have less than :value items.',
    ],
    'lte' => [
        'numeric' => 'The :attribute must be less than or equal :value.',
        'file' => 'The :attribute must be less than or equal :value kilobytes.',
        'string' => 'The :attribute must be less than or equal :value characters.',
        'array' => 'The :attribute must not have more than :value items.',
    ],
    'max' => [
        'numeric' => 'The :attribute may not be greater than :max.',
        'file' => 'The :attribute may not be greater than :max kilobytes.',
        'string' => 'The :attribute may not be greater than :max characters.',
        'array' => 'The :attribute may not have more than :max items.',
    ],
    'mimes' => 'The :attribute must be a file of type: :values.',
    'mimetypes' => 'The :attribute must be a file of type: :values.',
    'min' => [
        'numeric' => 'The :attribute must be at least :min.',
        'file' => 'The :attribute must be at least :min kilobytes.',
        'string' => 'The :attribute must be at least :min characters.',
        'array' => 'The :attribute must have at least :min items.',
    ],
    'not_in' => 'The selected :attribute is invalid.',
    'not_regex' => 'The :attribute format is invalid.',
    'numeric' => 'The :attribute must be a number.',
    'password' => 'The password is incorrect.',
    'present' => 'The :attribute field must be present.',
    'regex' => 'The :attribute format is invalid.',
    'required' => 'The :attribute field is required.',
    'required_if' => 'The :attribute field is required when :other is :value.',
    'required_unless' => 'The :attribute field is required unless :other is in :values.',
    'required_with' => ':attribute muss erfasst werden wenn :values angebenen wird.',
    'required_with_all' => 'The :attribute field is required when :values are present.',
    'required_without' => ':attribute muss erfasst werden, wenn :values nicht angegeben wird.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same' => 'The :attribute and :other must match.',
    'size' => [
        'numeric' => 'The :attribute must be :size.',
        'file' => 'The :attribute must be :size kilobytes.',
        'string' => 'The :attribute must be :size characters.',
        'array' => 'The :attribute must contain :size items.',
    ],
    'starts_with' => 'The :attribute must start with one of the following: :values.',
    'string' => 'The :attribute must be a string.',
    'timezone' => 'The :attribute must be a valid zone.',
    'unique' => 'The :attribute has already been taken.',
    'uploaded' => 'The :attribute failed to upload.',
    'url' => 'The :attribute format is invalid.',
    'uuid' => 'The :attribute must be a valid UUID.',
    'uppercase' => 'The :attribute must be uppercase.',
    'gameminute' => 'Games must start at quarter hours (00, 15, 30 or 45).',
    'gamehour' => 'Games must start between 8:00 and 22:00.',
    'sliderrange' => ':attribute must be in range beween :min and :max',
    'captcha' => 'This result is wrong.',

    'game_date_format' => 'Date should be formatted like d(d).m(m).yy(yy). E.g. 1.1.23, 01.01.23, 1.1.2023 or 01.01.2023',
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        // CLUB
        'shortname' => 'Code',
        'name' => 'Name',
        'club_no' => 'Club Number',
        'url' => 'URL',
        'inactive' => 'inactive',

        // GYM
        'name' => 'Name',
        'city' => 'City',
        'street' => 'Street',
        'zipcode' => 'ZIPcode',
        'zip' => 'ZIPcode',
        'gym_no' => 'Gym Number',

        // MEMBERSHIP
        'selRole' => 'Role of this member',
        'function' => 'Role details',
        'email' => 'eMail',

        // TEAM
        'team_no' => 'Team Number',
        'training_day' => 'Training day',
        'training_time' => 'Training begins at',
        'preferred_game_day' => 'Preferred game day',
        'preferred_game_time' => 'Preferred game start time',
        'gym_id' => 'Gym No',
        'league_prev' => 'Last years league',
        'shirt_color' => 'Shirt Color',

        // FILE DOWNLOAD
        'type' => 'Club or League',
        'club' => 'Club',
        'league' => 'League',
        'file' => 'Filename',

        // FEEDBACK
        'title' => 'Title',
        'body' => 'Bodytext',

        // LEAGUE
        'shortname' => 'Shortcode',
        'league_size_id' => 'Number of Teams',
        'schedule_id' => 'Schedule',
        'age_type' => 'Age group',
        'gender_type' => 'Gender',
        'from_state' => 'Current phase',
        'action' => 'Action',
        'assignedClubs' => 'Assigned Clubs',
        'assignedClubs.*' => 'Assigned Club',
        'club_id' => 'Club',
        'team_id' => 'Team',
        'league_id' => 'League',
        'league_no' => 'League Team Number',

        // MEMBER
        'member_id' => 'Member',
        'firstname' => 'Firstname',
        'lastname' => 'Lastname',
        'phone' => 'Phone no.',
        'fax' => 'Fax no.',
        'mobile' => 'Mobile Phone no.',
        'email1' => 'eMail (Main)',
        'email2' => 'eMail (2nd)',
        'role_id' => 'Function/Role',

        // MESSAGE
        'greeting' => 'Greeting',
        'salutation' => 'Salutation',
        'send_at' => 'Send at Date',
        'delete_at' => 'Delete at',
        'to_members' => 'To members',
        'to_members.*' => 'To member',
        'cc_members.*' => 'Copy to ',
        'notify_users' => 'Notify users',

        // Region
        'region' => 'Region',
        'region_id' => 'Region',
        'game_slot' => 'Gameslots (in Minutes)',
        'job_noleads' => 'Check on missing clubleads',
        'job_email_valid' => 'Check eMail-Address',
        'fmt_club_reports' => 'Fileformat Club reports',
        'fmt_club_reports.*' => 'Fileformat Club reports',
        'fmt_league_reports' => 'Fileformat League reports',
        'fmt_league_reports.*' => 'Fileformat League reports',
        'open_selection_at' => 'Start Team League No Selection',
        'close_selection_at' => 'End Team League No Selection',
        'open_scheduling_at' => 'Start Home Game Scheduling',
        'close_scheduling_at' => 'End Home Game Scheduling',
        'close_referees_at' => 'End Referee Assignment',

        // SCHEDULE
        'iterations' => 'Repeat n-times',
        'custom_events' => 'Custom Schedule',
        'startdate' => 'Start with date',
        'clone_from_schedule' => 'From schedule',
        'direction' => 'Direction (back/future)',
        'unit' => 'Unit of time',
        'unitRange' => 'Number',
        'gamedayRange' => 'Range of Gamedays',
        'gamedayRemoveRange' => 'Range of Gamedays',
        'gamedayAddRange' => 'Range of Gamedays',
        'full_weekend' => 'Full Weekend',
        'game_date' => 'Game Date',

        // USER
        'locale' => 'Sprache',
        'reason_reject' => 'Reason for rejection',
        'reason_join' => 'Reason for the acccount',
        'captcha' => 'Result',
        'region_ids' => 'Regions',
        'region_ids.*' => 'Region',
        'club_ids' => 'Clubs',
        'club_ids.*' => 'Club',
        'league_ids' => 'Leagues',
        'league_ids.*' => 'League',
        'member_id' => 'Members',
        'regionadmin' => 'Region access',
        'clubadmin' => 'Club access',
        'leagueadmin' => 'League access',
        'approved' => 'Approved',
        'password' => 'Password',

        // GAMES
        'game_no' => 'Game #',
        'team_id_home' => 'Home Team',
        'team_id_guest' => 'Guest Team',

        // CLUBGAME
        'gfile' => 'File with Games',
        'attachfile' => 'Fileattachment',

    ],

];

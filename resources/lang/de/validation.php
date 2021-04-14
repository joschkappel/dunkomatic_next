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

    'accepted'             => ':attribute muss akzeptiert werden.',
    'active_url'           => ':attribute ist keine korrekte URL.',
    'after'                => ':attribute muss ein Datum nach dem :date sein.',
    'after_or_equal'       => ':attribute muss ein Datum nach dem oder am :date sein.',
    'alpha'                => ':attribute darf nur Buchstaben enthalten.',
    'alpha_dash'           => ':attribute darf nur Buchstaben, Zahlen und Bindestriche enthalten.',
    'alpha_num'            => ':attribute darf nur Buchstaben und Zahlen enthalten.',
    'array'                => ':attribute muss eine Liste sein.',
    'before'               => ':attribute muss ein Datum vor dem :date sein.',
    'before_or_equal'      => ':attribute muss ein Datum vor dem oder am :date sein.',
    'between'              => [
        'numeric' => ':attribute muss zwischen :min und :max sein.',
        'file'    => ':attribute muss zwischen :min und :max Kilobytes sein.',
        'string'  => ':attribute muss zwischen :min und :max Zeichen sein.',
        'array'   => ':attribute muss zwischen :min und :max Eintr&auml;ge haben.',
    ],
    'boolean'              => ':attribute muss wahr oder falsch sein.',
    'confirmed'            => 'Die :attribute-Best&auml;tigung stimmt nicht &uuml;berein.',
    'date'                 => ':attribute ist kein g&uuml;ltiges Datum.',
    'date_equals'          => ':attribute muss gleich dem Datum :date sein.',
    'date_format'          => ':attribute entspricht nicht dem Format: :format.',
    'different'            => ':attribute und :other m&uuml;ssen verschieden sein.',
    'digits'               => ':attribute muss :digits Ziffern lang sein.',
    'digits_between'       => ':attribute muss zwischen :min und :max Ziffern lang sein.',
    'dimensions'           => ':attribute hat inkorrekte Bild-Dimensionen.',
    'distinct'             => ':attribute hat einen doppelten Wert.',
    'email'                => ':attribute muss eine korrekte E-Mail-Adresse sein.',
    'ends_with'            => ':attribute muss mit einem der folgenden Werte enden: :values.',
    'exists'               => 'Ausgew&auml;hlte(s) :attribute ist inkorrekt.',
    'file'                 => ':attribute muss eine Datei sein.',
    'filled'               => ':attribute muss ausgef&uuml;llt werden.',
    'gt' => [
        'numeric' => ':attribute muss größer als :value sein.',
        'file' => ':attribute muss größer als :value Kilobyte sein.',
        'string' => ':attribute muss mehr als :value Zeichen sein.',
        'array' => ':attribute muss mehr als :value Einträge haben.',
    ],
    'gte' => [
      'numeric' => ':attribute muss größer oder gleich :value sein.',
      'file' => ':attribute muss größer oder gleich :value Kilobyte sein.',
      'string' => ':attribute muss :value Zeichen oder mehr lang sein.',
      'array' => ':attribute muss :value oder mehr Einträge haben.',
    ],
    'image'                => ':attribute muss ein Bild sein.',
    'in'                   => 'Ausgewählte(s) :attribute ist inkorrekt.',
    'in_array'             => ':attribute existiert nicht in :other.',
    'integer'              => ':attribute muss eine Ganzzahl sein.',
    'ip'                   => ':attribute muss eine korrekte IP-Adresse sein.',
    'ipv4'                 => ':attribute muss eine korrekte IPv4-Adresse sein.',
    'ipv6'                 => ':attribute muss eine korrekte IPv6-Adresse sein.',
    'json'                 => ':attribute muss ein korrekter JSON-String sein.',
    'lt' => [
      'numeric' => ':attribute muss kleiner als :value sein.',
      'file' => ':attribute muss kleiner als :value Kilobyte sein.',
      'string' => ':attribute muss weniger als :value Zeichen lang sein.',
      'array' => ':attribute muss weniger als :value Einträge haben.',
    ],
    'lte' => [
      'numeric' => ':attribute muss kleiner oder gleich :value sein.',
      'file' => ':attribute muss kleiner oder gleich :value Kilobyte sein.',
      'string' => ':attribute muss :value oder weniger Zeichen lang sein.',
      'array' => ':attribute muss :value oder weniger Einträge haben.',
    ],
    'max'                  => [
        'numeric' => ':attribute darf nicht größer als :max sein.',
        'file'    => ':attribute darf nicht größer als :max Kilobytes sein.',
        'string'  => ':attribute darf nicht länger als :max Zeichen sein.',
        'array'   => ':attribute darf nicht mehr als :max Einträge enthalten.',
    ],
    'mimes'                => ':attribute muss eine Datei in folgendem Format sein: :values.',
    'mimetypes'            => ':attribute muss eine Datei in folgendem Format sein: :values.',
    'min'                  => [
        'numeric' => ':attribute muss mindestens :min sein.',
        'file'    => ':attribute muss mindestens :min Kilobytes groß; sein.',
        'string'  => ':attribute muss mindestens :min Zeichen lang sein.',
        'array'   => ':attribute muss mindestens :min Einträge haben..',
    ],
    'not_in'               => 'Ausgew&auml;hlte(s) :attribute ist inkorrekt.',
    'not_regex' => 'The :attribute format is invalid.',
    'numeric'              => ':attribute muss eine Zahl sein.',
    'present'              => ':attribute muss vorhanden sein.',
    'regex'                => 'Das :attribute-Format ist inkorrekt.',
    'required'             => ':attribute wird benötigt.',
    'required_if'          => ':attribute wird benötigt, wenn :other einen Wert von :value hat.',
    'required_unless'      => ':attribute wird benötigt, außer :other ist in den Werten :values enthalten.',
    'required_with'        => ':attribute wird benötigt, wenn :values vorhanden ist.',
    'required_with_all'    => ':attribute wird benötigt, wenn alle values vorhanden sind.',
    'required_without'     => ':attribute wird benötigt, wenn :values nicht vorhanden ist.',
    'required_without_all' => ':attribute wird benötigt, wenn keine der Werte :values vorhanden ist.',
    'same'                 => ':attribute und :other müssen gleich sein.',
    'size'                 => [
        'numeric' => ':attribute muss :size groß; sein.',
        'file'    => ':attribute muss :size Kilobytes groß sein.',
        'string'  => ':attribute muss :size Zeichen lang sein.',
        'array'   => ':attribute muss :size Einträge enthalten.',
    ],
    'starts_with'          => ':attribute muss mit einem der folgenden Werte beginnen: :values.',
    'string'               => ':attribute muss Text sein.',
    'timezone'             => ':attribute muss eine korrekte Zeitzone sein.',
    'unique'               => ':attribute wurde bereits verwendet.',
    'uploaded'             => 'Der Upload von :attribute schlug fehl.',
    'url'                  => 'Das :attribute-Format ist inkorrekt.',
    'uuid'                 => ':attribute muss eine gültige UUID sein.',
    'uppercase'            => ':attribute darf nur Großbuchstaben enthalten.',
    'gameminute'           => 'Spielbeginn nur zur vollen Viertelstunde (00, 15, 30 oder 45).',
    'gamehour'             => 'Spielbeginn muss zwischen 8:00 und 22:00 Uhr sein',
    'sliderrange'          => ':attribute muss zwischen :min und :max liegen',



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
      'shortname' => 'Code',
      'name' => 'Name',
      'club_no' => 'Vereinsnummer',
      'region' => 'Bezirk',
      'firstname' => 'Vorname',
      'lastname' => 'Nachname',
      'phone1' => 'Telefonnr.',
      'phone2' => 'Alternative Telefonnr.',
      'mobile' => 'Mobilnummer',
      'city'  => 'Stadt',
      'street' => 'Straße und Hausnr.',
      'zipcode' => 'Postleitzahl',
      'zip' => 'Postleitzahl',
      'gym_no' => 'Hallennummer',

    ],

];

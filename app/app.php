
 <?php



    require_once __DIR__.'/../vendor/autoload.php';
    require_once __DIR__.'/../src/User.php';
    require_once __DIR__.'/../src/Record.php';
    session_start();

    // $server = 'mysql:host=localhost;dbname=discogs';
    // $user = 'root';
    // $password = 'root';
    // $DB = new PDO($server, $user, $password);

    $url = parse_url(getenv("CLEARDB_DATABASE_URL"));

    $server = $url["host"];
    $username = $url["user"];
    $password = $url["pass"];
    $db = substr($url["path"], 1);

    $conn = new mysqli($server, $username, $password, $db);


    // session_start();
    // if( isset($_SESSION['search_term'])){
    //   echo "set";
    // }
    use Symfony\Component\Debug\Debug;
    Debug::enable();
    $app = new Silex\Application();

    if(!isset($_SESSION['user'])){
        $_SESSION['user'] = null;
    }
        $app['debug'] = true;

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__ . '/../views',
    ));

    $app->get("/", function() use ($app){
      $consumerKey = 'sgLbtXTMMDiImTNCBXgm';
      $consumerSecret = 'EzoLruPOcgrPzIYtiqARnBmbfNPsLYvN';
      $token = 'AlgbUBFeznIfeIvjzNEIvmFmiDQGWHtbgrFJuAGC';
      $url = "https://api.discogs.com/";
      $error = null;
      $results_url = $url . '/database/search?q=&per_page=50&key='. $consumerKey . '&secret=' . $consumerSecret;

      $ch = curl_init();
      //Set the User-Agent Identifier
      curl_setopt($ch, CURLOPT_USERAGENT, 'CRATE/0.1 +http://your-site-here.com');
      //Set the URL of the page or file to download.
      curl_setopt($ch, CURLOPT_URL, $results_url);
      //Ask cURL to return the contents in a variable instead of simply echoing them
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      //Execute the curl session
      $output = curl_exec($ch);
      //close the session
      curl_close ($ch);



      $results_array = json_decode($output, true);
      $pages_array = $results_array['pagination'];
        return $app['twig']->render("index.html.twig", array(
            'error' => $error,
            'user' => $_SESSION['user'],
            'results' => $results_array['results'],
            'pages' => $pages_array
        ));
    });

    $app->get("/search", function() use ($app){
        $consumerKey = 'sgLbtXTMMDiImTNCBXgm';
        $consumerSecret = 'EzoLruPOcgrPzIYtiqARnBmbfNPsLYvN';
        $token = 'AlgbUBFeznIfeIvjzNEIvmFmiDQGWHtbgrFJuAGC';
        $url = "https://api.discogs.com/"; // add the resource info to the url. Ex. releases/1
        $search_term = $_GET["search_term"];
        $_SESSION['search_term'] = $search_term;


        if(isset($_GET['genre'])){
            $results_url = $url . '/database/search?genre='. urlencode($_SESSION['search_term']) . '&per_page=50&key='. $consumerKey . '&secret=' . $consumerSecret;
            //initialize the session
            $ch = curl_init();
            //Set the User-Agent Identifier
            curl_setopt($ch, CURLOPT_USERAGENT, 'CRATE/0.1 +http://your-site-here.com');
            //Set the URL of the page or file to download.
            curl_setopt($ch, CURLOPT_URL, $results_url);
            //Ask cURL to return the contents in a variable instead of simply echoing them
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //Execute the curl session
            $output = curl_exec($ch);
            //close the session
            curl_close ($ch);

            $results_array = json_decode($output, true);
        } else if (isset($_GET['artist'])) {
            $results_url = $url . '/database/search?artist='. urlencode($_SESSION['search_term']) . '&key='. $consumerKey . '&secret=' . $consumerSecret;
            //initialize the session
            $ch = curl_init();
            //Set the User-Agent Identifier
            curl_setopt($ch, CURLOPT_USERAGENT, 'CRATE/0.1 +http://your-site-here.com');
            //Set the URL of the page or file to download.
            curl_setopt($ch, CURLOPT_URL, $results_url);
            //Ask cURL to return the contents in a variable instead of simply echoing them
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //Execute the curl session
            $output = curl_exec($ch);
            //close the session
            curl_close ($ch);

            $results_array = json_decode($output, true);
        } else if (isset($_GET['year'])) {
            $results_url = $url . '/database/search?year='. urlencode($_SESSION['search_term']) . '&per_page=50&key='. $consumerKey . '&secret=' . $consumerSecret;
            //initialize the session
            $ch = curl_init();
            //Set the User-Agent Identifier
            curl_setopt($ch, CURLOPT_USERAGENT, 'CRATE/0.1 +http://your-site-here.com');
            //Set the URL of the page or file to download.
            curl_setopt($ch, CURLOPT_URL, $results_url);
            //Ask cURL to return the contents in a variable instead of simply echoing them
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //Execute the curl session
            $output = curl_exec($ch);
            //close the session
            curl_close ($ch);

            $results_array = json_decode($output, true);
        } else {
            $results_url = $url . '/database/search?type=release&q='. urlencode($_SESSION['search_term']) . '&per_page=50&key='. $consumerKey . '&secret=' . $consumerSecret;
            //initialize the session
            $ch = curl_init();
            //Set the User-Agent Identifier
            curl_setopt($ch, CURLOPT_USERAGENT, 'CRATE/0.1 +http://your-site-here.com');
            //Set the URL of the page or file to download.
            curl_setopt($ch, CURLOPT_URL, $results_url);
            //Ask cURL to return the contents in a variable instead of simply echoing them
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //Execute the curl session
            $output = curl_exec($ch);
            //close the session
            curl_close ($ch);

            $results_array = json_decode($output, true);
            // print_r($results_array);
        }
        $pages_array = $results_array['pagination'];
        // print_r($results_array);
        return $app['twig']->render("search.html.twig", array(
            'user' => $_SESSION['user'],
            'results' => $results_array['results'],
            'pages' => $pages_array
        ));
    });

    $app->get("/search/{page}", function($page) use ($app){
        // print_r($_SESSION['search_term']);
        $consumerKey = 'sgLbtXTMMDiImTNCBXgm';
        $consumerSecret = 'EzoLruPOcgrPzIYtiqARnBmbfNPsLYvN';
        $token = 'AlgbUBFeznIfeIvjzNEIvmFmiDQGWHtbgrFJuAGC';
        $url = "https://api.discogs.com/"; // add the resource info to the url. Ex. releases/1


        $results_url = $url . '/database/search?q=' . urlencode($_SESSION['search_term']) . '&per_page=50&secret=' . $consumerSecret . '&page='. $page . '&key='. $consumerKey;
        //initialize the session
        $ch = curl_init();
        //Set the User-Agent Identifier
        curl_setopt($ch, CURLOPT_USERAGENT, 'CRATE/0.1 +http://your-site-here.com');
        //Set the URL of the page or file to download.
        curl_setopt($ch, CURLOPT_URL, $results_url);
        //Ask cURL to return the contents in a variable instead of simply echoing them
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //Execute the curl session
        $output = curl_exec($ch);
        //close the session
        curl_close ($ch);

        $results_array = json_decode($output, true);
        $pages_array = $results_array['pagination'];

        return $app['twig']->render("search.html.twig", array(
            'user' => $_SESSION['user'],
            'results' => $results_array['results'],
            'pages' => $pages_array
        ));
    });

    $app->get("/release/{id}", function($id) use ($app){
        // https://api.discogs.com/artists/1982526
        $consumerKey = 'sgLbtXTMMDiImTNCBXgm';
        $consumerSecret = 'EzoLruPOcgrPzIYtiqARnBmbfNPsLYvN';
        $token = 'AlgbUBFeznIfeIvjzNEIvmFmiDQGWHtbgrFJuAGC';
        $url = "https://api.discogs.com/";
        $_SESSION['release_id'] = $id;
        $results_url = $url . '/releases/' . $_SESSION['release_id'] . '?key=' . $consumerKey . '&secret=' . $consumerSecret;
        // $_SESSION['']
        // results curl
        $ch = curl_init();
        //Set the User-Agent Identifier
        curl_setopt($ch, CURLOPT_USERAGENT, 'CRATE/0.1 +http://your-site-here.com');
        //Set the URL of the page or file to download.
        curl_setopt($ch, CURLOPT_URL, $results_url);
        //Ask cURL to return the contents in a variable instead of simply echoing them
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //Execute the curl session
        $output = curl_exec($ch);
        //close the session
        curl_close ($ch);

        $results_array = json_decode($output, true);

        $error = null;
        return $app['twig']->render("release.html.twig", array(
            'user' => $_SESSION['user'],
            'error' => $error,
            'results' => $results_array,
            'label' => $results_array['labels'][0],
            'year' => $results_array['year'],
            'genres' => $results_array['genres'],
            'tracklist' => $results_array['tracklist'],
            'artist' => $results_array['artists'][0],
            'images' => $results_array['images']

        ));
    });


        //LOGIN ROUTE
        $app->get("/login", function() use ($app){
            $user_name = $_GET['user_name'];
            $password = $_GET['password'];
            $user = User::login($user_name, $password);
            $_SESSION['user'] = $user;
            // print_r(User::getAll());
            if (isset($_SESSION['user'])){
                return $app['twig']->render("collection.html.twig", array(
                    'user' => $_SESSION['user'],
                    'collection' => $user->getRecords()
                ));
            } else {
                $consumerKey = 'sgLbtXTMMDiImTNCBXgm';
                $consumerSecret = 'EzoLruPOcgrPzIYtiqARnBmbfNPsLYvN';
                $token = 'AlgbUBFeznIfeIvjzNEIvmFmiDQGWHtbgrFJuAGC';
                $url = "https://api.discogs.com/";

                $results_url = $url . '/database/search?q=&per_page=50&key='. $consumerKey . '&secret=' . $consumerSecret;

                $ch = curl_init();
                //Set the User-Agent Identifier
                curl_setopt($ch, CURLOPT_USERAGENT, 'CRATE/0.1 +http://your-site-here.com');
                //Set the URL of the page or file to download.
                curl_setopt($ch, CURLOPT_URL, $results_url);
                //Ask cURL to return the contents in a variable instead of simply echoing them
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                //Execute the curl session
                $output = curl_exec($ch);
                //close the session
                curl_close ($ch);

                $results_array = json_decode($output, true);
                $error = "Incorrect login info.";
                return $app['twig']->render("index.html.twig", array(
                    'error' => $error,
                    'results' => $results_array['results']
            ));
            }
        });

        $app->get("/logout", function() use ($app){
            $_SESSION['user'] = null;
            $error = "Successfully logged out.";
            $consumerKey = 'sgLbtXTMMDiImTNCBXgm';
            $consumerSecret = 'EzoLruPOcgrPzIYtiqARnBmbfNPsLYvN';
            $token = 'AlgbUBFeznIfeIvjzNEIvmFmiDQGWHtbgrFJuAGC';
            $url = "https://api.discogs.com/";
            $error = null;
            $results_url = $url . '/database/search?q=&per_page=50&key='. $consumerKey . '&secret=' . $consumerSecret;

            $ch = curl_init();
            //Set the User-Agent Identifier
            curl_setopt($ch, CURLOPT_USERAGENT, 'CRATE/0.1 +http://your-site-here.com');
            //Set the URL of the page or file to download.
            curl_setopt($ch, CURLOPT_URL, $results_url);
            //Ask cURL to return the contents in a variable instead of simply echoing them
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //Execute the curl session
            $output = curl_exec($ch);
            //close the session
            curl_close ($ch);



            $results_array = json_decode($output, true);
            $pages_array = $results_array['pagination'];
            return $app['twig']->render("index.html.twig", array(
                'user' => $_SESSION['user'],
                'error' => $error,
                'results' => $results_array['results'],
                'pages' => $pages_array
            ));
        });

        //VIEW SINGLE RECORD ROUTE
        $app->get("/view_record/{id}", function($id) use ($app){
            $record = Record::find($id);
            return $app['twig']->render("record.html.twig", array('record' => $record));
        });

        //ADD RECORD TO COLLECTION ROUTE
        $app->post("/add_record", function() use ($app){
            $consumerKey = 'sgLbtXTMMDiImTNCBXgm';
            $consumerSecret = 'EzoLruPOcgrPzIYtiqARnBmbfNPsLYvN';
            $token = 'AlgbUBFeznIfeIvjzNEIvmFmiDQGWHtbgrFJuAGC';
            $url = "https://api.discogs.com/";
            $results_url = $url . '/releases/' . $_SESSION['release_id'] . '?key=' . $consumerKey . '&secret=' . $consumerSecret;
            // $_SESSION['']
            // results curl
            $ch = curl_init();
            //Set the User-Agent Identifier
            curl_setopt($ch, CURLOPT_USERAGENT, 'CRATE/0.1 +http://your-site-here.com');
            //Set the URL of the page or file to download.
            curl_setopt($ch, CURLOPT_URL, $results_url);
            //Ask cURL to return the contents in a variable instead of simply echoing them
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //Execute the curl session
            $output = curl_exec($ch);
            //close the session
            curl_close ($ch);


            $results_array = json_decode($output, true);
            $tracks = array();
            $labels = array();
            $images = array();

            foreach($results_array['tracklist'] as $track){
                array_push($tracks, $track['title']);
            }
            foreach($results_array['labels'] as $label){
                array_push($labels, $label['name']);
            }
            foreach($results_array['images'] as $image){
                array_push($images, $image['resource_url']);
            }
            // print_r($results_array['artists'][0]['name']);
            $title = $results_array['tracklist'][0]['title'];
            $artist = $results_array['artists'][0]['name'];
            $genre = $results_array['genres'][0];
            $track = implode($tracks);
            $year = $results_array['year'];
            $label = $labels[0];
            $images = $images[0];
            $id = null;
            $error = null;



            if(isset($_SESSION['user'])){
                $records = $_SESSION['user']->getRecords();

                if(!$records){
                    $error = null;
                    $new_record = new Record($title, $artist, $genre, $track, $year, $images, $label, $id);
                    $new_record->save();
                    $_SESSION['user']->addRecord($new_record);
                    return $app['twig']->render("release.html.twig", array(
                        'error' => $error,
                        'user' => $_SESSION['user'],
                        'results' => $results_array,
                        'label' => $results_array['labels'][0],
                        'year' => $results_array['year'],
                        'genres' => $results_array['genres'],
                        'tracklist' => $results_array['tracklist'],
                        'artist' => $results_array['artists'][0],
                        'images' => $results_array['images']));
                }
                foreach($records as $record){

                    $record_name = $record->getTitle();
                    $record_track = explode(',', $record->getTrack());
                    $record_artist = $record->getArtist();
                    if($record_name == $title && $record_artist == $artist){

                        $error = "Already in your collection";
                        return $app['twig']->render("release.html.twig", array(
                            'error' => $error,
                            'user' => $_SESSION['user'],
                            'results' => $results_array,
                            'label' => $results_array['labels'][0],
                            'year' => $results_array['year'],
                            'genres' => $results_array['genres'],
                            'tracklist' => $results_array['tracklist'],
                            'artist' => $results_array['artists'][0],
                            'images' => $results_array['images']
                        ));
                    } else {
                        $error = null;
                        $new_record = new Record($title, $artist, $genre, $track, $year, $images, $label, $id);
                        $new_record->save();
                        $_SESSION['user']->addRecord($new_record);
                        return $app['twig']->render("release.html.twig", array(
                            'error' => $error,
                            'user' => $_SESSION['user'],
                            'results' => $results_array,
                            'label' => $results_array['labels'][0],
                            'year' => $results_array['year'],
                            'genres' => $results_array['genres'],
                            'tracklist' => $results_array['tracklist'],
                            'artist' => $results_array['artists'][0],
                            'images' => $results_array['images']));
                    }
                }
            } elseif(!isset($_SESSION['user'])) {
                        $error = "Please login to use feature";
                        return $app['twig']->render("release.html.twig", array(
                            'error' => $error,
                            'user' => $_SESSION['user'],
                            'results' => $results_array,
                            'label' => $results_array['labels'][0],
                            'year' => $results_array['year'],
                            'genres' => $results_array['genres'],
                            'tracklist' => $results_array['tracklist'],
                            'artist' => $results_array['artists'][0],
                            'images' => $results_array['images']));
            }
            return $app['twig']->render("release.html.twig", array(
                'error' => $error,
                'user' => $_SESSION['user'],
                'results' => $results_array,
                'label' => $results_array['labels'][0],
                'year' => $results_array['year'],
                'genres' => $results_array['genres'],
                'tracklist' => $results_array['tracklist'],
                'artist' => $results_array['artists'][0],
                'images' => $results_array['images']
            ));
        });

        //DELETE RECORD FROM COLLECTION (FROM RELEASE PAGE)
        $app->post("/delete_record", function() use ($app){
            $consumerKey = 'sgLbtXTMMDiImTNCBXgm';
            $consumerSecret = 'EzoLruPOcgrPzIYtiqARnBmbfNPsLYvN';
            $token = 'AlgbUBFeznIfeIvjzNEIvmFmiDQGWHtbgrFJuAGC';
            $url = "https://api.discogs.com/";
            $results_url = $url . '/releases/' . $_SESSION['release_id'] . '?key=' . $consumerKey . '&secret=' . $consumerSecret;
            // $_SESSION['']
            // results curl
            $ch = curl_init();
            //Set the User-Agent Identifier
            curl_setopt($ch, CURLOPT_USERAGENT, 'CRATE/0.1 +http://your-site-here.com');
            //Set the URL of the page or file to download.
            curl_setopt($ch, CURLOPT_URL, $results_url);
            //Ask cURL to return the contents in a variable instead of simply echoing them
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //Execute the curl session
            $output = curl_exec($ch);
            //close the session
            curl_close ($ch);

            // var_dump($records);
            $results_array = json_decode($output, true);
            $tracks = array();
            $labels = array();
            $images = array();

            foreach($results_array['tracklist'] as $track){
                array_push($tracks, $track['title']);
            }
            foreach($results_array['labels'] as $label){
                array_push($labels, $label['name']);
            }
            foreach($results_array['images'] as $image){
                array_push($images, $image['resource_url']);
            }

            $title = $results_array['tracklist'][0]['title'];
            $artist = $results_array['artists'][0]['name'];
            $genre = $results_array['genres'][0];
            $track = implode($tracks);
            $year = $results_array['year'];
            $label = $labels[0];
            $images = $images[0];
            $id = null;

            if(isset($_SESSION['user'])){
                $records = $_SESSION['user']->getRecords();
                foreach($records as $record){
                    $record_name = $record->getTitle();
                    $record_artist = $record->getArtist();
                    if($record_name == $title && $record_artist == $artist ){
                        $record->delete();
                        $error = "Record deleted";
                        return $app['twig']->render("release.html.twig", array(
                            'error' => $error,
                            'user' => $_SESSION['user'],
                            'results' => $results_array,
                            'label' => $results_array['labels'][0],
                            'year' => $results_array['year'],
                            'genres' => $results_array['genres'],
                            'tracklist' => $results_array['tracklist'],
                            'artist' => $results_array['artists'][0],
                            'images' => $results_array['images']
                        ));
                    }
                }
                    $error = "Not in your collection";
                    return $app['twig']->render("release.html.twig", array(
                                'error' => $error,
                                'user' => $_SESSION['user'],
                                'results' => $results_array,
                                'label' => $results_array['labels'][0],
                                'year' => $results_array['year'],
                                'genres' => $results_array['genres'],
                                'tracklist' => $results_array['tracklist'],
                                'artist' => $results_array['artists'][0],
                                'images' => $results_array['images']
                            ));


                } elseif(!isset($_SESSION['user'])) {
                    $error = "Please login to use feature.";
                    return $app['twig']->render("release.html.twig", array(
                                'error' => $error,
                                'user' => $_SESSION['user'],
                                'results' => $results_array,
                                'label' => $results_array['labels'][0],
                                'year' => $results_array['year'],
                                'genres' => $results_array['genres'],
                                'tracklist' => $results_array['tracklist'],
                                'artist' => $results_array['artists'][0],
                                'images' => $results_array['images']
                            ));
                }
        });

        //COLLECTION ROUTE
        $app->get("/collection", function() use ($app){
            $user = $_SESSION['user'];
            return $app['twig']->render("collection.html.twig", array(
                'collection' => $user->getRecords(),
                'user' => $user
            ));
        });

        //VIEW SINGLE RECORD FROM COLLECTION
        $app->get("/collection/record/{id}", function($id) use($app){
            $user = $_SESSION['user'];
            $record = Record::find($id);
            $tracks = explode(',', $record->getTrack());
            return $app['twig']->render("record.html.twig", array(
                'user' => $user,
                'record' => $record,
                'tracks' => $tracks
            ));
        });

        //DELETE RECORD FROM COLLECTION (FROM COLLECTION PAGE)
        $app->post("/delete_record_from_db/{id}", function($id) use ($app){
            $record = Record::find($id);
            $record->delete();
            $user = $_SESSION['user'];
            return $app['twig']->render("collection.html.twig", array('collection' => $user->getRecords(), 'user' => $user));
        });

        // REGISTER NEW ACCOUNT
        $app->post("/register", function () use ($app){
            $username = $_POST['user_name'];
            $password = $_POST['password'];
            $new_user = new User($username, $password);
            $new_user->save();
            $_SESSION['user'] = $new_user;
            return $app['twig']->render("collection.html.twig", array('collection' => $new_user->getRecords(), 'user' => $new_user));
        });


    return $app;
?>



<!-- psuedo code -->
<!-- "community": {"status": "Accepted", "rating": {"count": 237, "average": 4.77} -->

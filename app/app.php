 <?php
    session_start();

    require_once __DIR__.'/../vendor/autoload.php';
    require_once __DIR__.'/../src/User.php';
    require_once __DIR__.'/../src/Record.php';


    $server = 'mysql:host=localhost:8889;dbname=discogs';
    $user = 'root';
    $password = 'root';
    $DB = new PDO($server, $user, $password);



    $app = new Silex\Application();


    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__ . '/../views',
    ));

    $app->get("/", function() use ($app){
        return $app['twig']->render("index.html.twig", array(
            'users' => User::getAll()
        ));
    });

    $app->get("/search", function() use ($app){
        $consumerKey = 'sgLbtXTMMDiImTNCBXgm';
        $consumerSecret = 'EzoLruPOcgrPzIYtiqARnBmbfNPsLYvN';
        $token = 'AlgbUBFeznIfeIvjzNEIvmFmiDQGWHtbgrFJuAGC';
        $url = "https://api.discogs.com/"; // add the resource info to the url. Ex. releases/1
        if(isset($_GET['genre'])){
            $results_url = $url . '/database/search?genre='. urlencode($_GET["search_term"]) . '&key='. $consumerKey . '&secret=' . $consumerSecret;
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
            $results_url = $url . '/database/search?artist='. urlencode($_GET["search_term"]) . '&key='. $consumerKey . '&secret=' . $consumerSecret;
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
            $results_url = $url . '/database/search?year='. urlencode($_GET["search_term"]) . '&key='. $consumerKey . '&secret=' . $consumerSecret;
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
            $results_url = $url . '/database/search?q='. urlencode($_GET["search_term"]) . '&key='. $consumerKey . '&secret=' . $consumerSecret;
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
        }
        $pages_array = $results_array['pagination'];
        print_r($results_array);
        return $app['twig']->render("index.html.twig", array(
            'users' => User::getAll(),
            'results' => $results_array['results'],
            'pages' => $pages_array
        ));
    });

    $app->get("/search/{page}", function($page) use ($app){
        $consumerKey = 'sgLbtXTMMDiImTNCBXgm';
        $consumerSecret = 'EzoLruPOcgrPzIYtiqARnBmbfNPsLYvN';
        $token = 'AlgbUBFeznIfeIvjzNEIvmFmiDQGWHtbgrFJuAGC';
        $url = "https://api.discogs.com/"; // add the resource info to the url. Ex. releases/1

        $results_url = $url . '/database/search?q=' . urlencode($_GET["search_term"]). '&per_page=50&secret=' . $consumerSecret . '&page='. $page . '&key='. $consumerKey;
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

        return $app['twig']->render("index.html.twig", array(
            'users' => User::getAll(),
            'results' => $results_array['results'],
            'pages' => $pages_array
        ));
    });

    $app->get("/artist/{id}", function($id) use ($app){
        // https://api.discogs.com/artists/1982526
        $consumerKey = 'sgLbtXTMMDiImTNCBXgm';
        $consumerSecret = 'EzoLruPOcgrPzIYtiqARnBmbfNPsLYvN';
        $token = 'AlgbUBFeznIfeIvjzNEIvmFmiDQGWHtbgrFJuAGC';
        $url = "https://api.discogs.com/";

        $results_url = $url . '/artists/' . $id . '?key=' . $consumerKey . '&secret=' . $consumerSecret;
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


        // releases curl
        $releases_url = $url . '/artists/' . $id .'/releases'. '?key=' . $consumerKey . '&secret=' . $consumerSecret;
        $ch2 = curl_init();
        //Set the User-Agent Identifier
        curl_setopt($ch2, CURLOPT_USERAGENT, 'CRATE/0.1 +http://your-site-here.com');
        //Set the URL of the page or file to download.
        curl_setopt($ch2, CURLOPT_URL, $releases_url);
        //Ask cURL to return the contents in a variable instead of simply echoing them
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
        //Execute the curl session
        $output = curl_exec($ch2);
        //close the session
        curl_close ($ch2);

        $releases_array = json_decode($output, true);
        // print_r($releases_array['releases']);
        return $app['twig']->render("artist_bio.html.twig", array(
            'users' => User::getAll(),
            'results' => $results_array,
            'images' => $results_array['images'],
            'releases' => $releases_array['releases']
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
                return $app['twig']->render("index.html.twig", array('user' => $_SESSION['user']));
            } else {
                $error = "Incorrect login info.";
                return $app['twig']->render("index.html.twig", array('error' => $error));
            }
        });

        //VIEW SINGLE RECORD ROUTE
        $app->get("/view_record/{id}", function($id) use ($app){
            $record = Record::find($id);
            return $app['twig']->render("record.html.twig", array('record' => $record));
        });

        //ADD RECORD TO COLLECTION ROUTE
        $app->post("/add_record", function() use ($app){
            $record = Record::find($_POST['record_id']);
            $user = User::find($_POST['user_id']);
            $user->addRecord($record);
            return $app['twig']->render("index.html.twig");
        });

        //COLLECTION ROUTE
        $app->get("/view_collection/{id}", function($id) use ($app){
            $user = User::find($id);
            return $app['twig']->render("collection.html.twig", array('records' => $user->getRecords()));
        });


    return $app;
?>

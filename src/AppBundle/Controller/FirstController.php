<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Model\GameMgt;
// use AppBundle\Model\Tower;
use AppBundle\Model\SniperTower;
use AppBundle\Model\FireTower;


class FirstController extends Controller
{

/**
* @Route("/games/{gameid}/addpiece", name="game_add_piece")
*/
  public function AddPieceAction(Request $request, $gameid) {
    $game = new GameMgt($this->container->get('snc_redis.default')) ;
    $game = $game->getById($gameid) ;

    $tower = new SniperTower() ;
    $tower2 = new FireTower() ;

    $ids = array($gameid, $tower->getId(), $tower2->getId()) ;

    $game->addPiece(array('lat'=>43.366303, 'lon'=>5.328117), $tower) ;
    $game->addPiece(array('lat'=>46.366303, 'lon'=>7.328117), $tower2) ;

    // Do we have to redirect to given URL ? (is an URL given?)
    if($request->query->get('returnURL')) {
      $this->addFlash("notice","You just added a piece to game #".$game->getId()) ;
      return $this->redirect($request->query->get('returnURL')) ;
    }

    return $this->json($ids) ;
  }

  /**
  * @Route("/games/{gameid}/detail", name="game_detail")
  */
  public function GameDetailAction(Request $request, $gameid) {

    // Getting game data from session
    // // Getting session handler
    // $current_sess = $request->getSession() ;
    // $session_games = $current_sess->get('created_games', array()) ;

    $game = new GameMgt($this->container->get('snc_redis.default')) ;
    $game = $game->getById($gameid) ;

    return $this->render('default/game_detail.html.twig', [
        'gameid' => $gameid,
        'gamedata' => print_r($game->toArray(), true)
    ]);
  }


  /**
  * @Route("/ws/game/create", name="game_creation")
  */
  public function GameCreateAction(Request $request) {

    // Getting session handler
    $current_sess = $request->getSession() ;

    // Creating new game
    $game = new GameMgt($this->container->get('snc_redis.default')) ;
    // with proper creator id
    $game->setCreator($current_sess->getId()) ;
    $game->build() ;

    // Storing new game in session
    $session_games = $current_sess->get('created_games', array()) ; // will be empty array if null
    $session_games[$game->getId()] = $game->toArray(true) ;
    $current_sess->set('created_games', $session_games) ;

    // Do we have to redirect to given URL ? (is an URL given?)
    if($request->query->get('returnURL')) {
      $this->addFlash("notice","You just created game #".$game->getId()) ;
      return $this->redirect($request->query->get('returnURL')) ;
    }

    // Finally, return list of games from session, as json string
    return $this->json($current_sess->get('created_games')) ;
  }

    /**
     * @Route("/home", name="homepage")
     */
    public function homeAction(Request $request)
    {
        $current_sess = $request->getSession() ;

        $req = array(
            $request->isXmlHttpRequest(), // is it an Ajax request?

            $request->getPreferredLanguage(array('en', 'fr')),

            // retrieve GET and POST variables respectively
            $request->query->get('page'),
            $request->request->get('page'),

            // retrieve SERVER variables
            $request->server->get('HTTP_HOST'),

            // retrieves an instance of UploadedFile identified by foo
            $request->files->get('foo'),

            // retrieve a COOKIE value
            $request->cookies->get('PHPSESSID'),

            // retrieve an HTTP request header, with normalized, lowercase keys
            $request->headers->get('host'),
            $request->headers->get('content_type'),
        ) ;

        // Fetch all games from DB
        $game = new GameMgt($this->container->get('snc_redis.default')) ;
        $allgames = $game->getAll() ;

        return $this->render('default/home.html.twig', [
            'created_games' => $current_sess->get('created_games', array()), // will be empty array if null
            'allgames' => $allgames
        ]);
    }
}

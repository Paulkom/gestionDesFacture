<?php

namespace App\Controller;

use App\Database\NativeQueryMySQL;
use App\Entity\Utilisateur;
use App\Repository\MenuRepository;
use App\Services\LibrairieService;
use App\Services\Parameters;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Twig\Environment;

class FonctionBaseController extends AbstractController
{
    protected $em;
    protected $columnsDefault = [];
    protected $tokenManager;
    protected $passwordHasher;
    private $security;
    private $session;
    private $parameters;


    
    public function __construct(Security $security, Parameters $parameters, EntityManagerInterface $entityM,UserPasswordHasherInterface $passwordHasher,CsrfTokenManagerInterface $tokenManager,)
    {
        $this->security = $security;
        $this->tokenManager = $tokenManager;
        $this->session = []; //$sessionInterface;
        $this->parameters = $parameters;
        $this->passwordHasher =$passwordHasher;
        $this->em = $entityM;
    }


    #[Route('/auteurSortie/load/options-json', name: 'app_auteurSortie_select')]
    public function loadAuteurSortie(Request $request)
    {
        $parameters = $request->getMethod() === 'POST' ? $request->request->all() : $request->query->all();
        $search = '';

        if(isset($parameters['search']) && !empty($parameters['search']))
            $search = $parameters['search'];

        $results = $this->em->createQuery("
            SELECT p.id, CONCAT(p.nom, ' ', p.prenom) AS text
            FROM App\Entity\Utilisateur p
            WHERE CONCAT(p.nom, ' ', p.prenom) LIKE '%$search%'"
        )->getArrayResult();

        return new JsonResponse($results);
    }

    #[Route('/fonction/base', name: 'app_fonction_base')]
    public function index(): Response
    {
        return $this->render('fonction_base/index.html.twig', [
            'controller_name' => 'FonctionBaseController',
        ]);
    }

         /**
     * @param $data
     * @return array
     */
    public function reformat($data): array
    {
        return array_map(function ($item) {
            $keys = array_keys($item);
            foreach ($keys as $key) {
                if($item[$key] instanceof \DateTime)
                    $item[$key] = $item[$key]->format('d/m/Y');

                if($item[$key] == "En attente")
                    $item[$key] = "<div class='badge badge-secondary fw-bold'>$item[$key]</div>";
                
                if($item[$key]=="payer")
                    $item[$key] = "<div class='badge badge-success fw-bold'>$item[$key]</div>";

                if($item[$key]=="Partiel")
                    $item[$key] = "<div class='badge badge-primary fw-bold'>$item[$key]</div>";

                if($item[$key]=="Annuler")
                    $item[$key] = "<div class='badge badge-danger fw-bold'>$item[$key]</div>";


                if($item[$key] == "Partielle")
                    $item[$key] = "<div class='badge badge-primary fw-bold'>$item[$key]</div>";
                
                if($item[$key]=="Terminée")
                    $item[$key] = "<div class='badge badge-success fw-bold'>$item[$key]</div>";

                if($item[$key]=="Non livrée")
                    $item[$key] = "<div class='badge badge-danger fw-bold'>$item[$key]</div>";

                if($item[$key]=="FA")
                    $item[$key] = "<div class='badge badge-danger fw-bold'> Fact. Avoir</div>";
                
                if($item[$key]=="FV")
                    $item[$key] = "<div class='badge badge-success fw-bold'> Fact. Vente </div>";

                if($item[$key]=="EV")
                    $item[$key] = "<div class='badge badge-success fw-bold'> Fact. Vente Exportat* </div>";

                if($item[$key]=="EA")
                    $item[$key] = "<div class='badge badge-danger fw-bold'> Fact. Avoir Exportat* </div>";

                if(gettype($item[$key]) === 'boolean') {
                    $value = $item[$key] ? 1 : 0;
                    $checked = $item[$key] ? "checked" : "";
                    $item[$key] = '
                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                            <input class="form-check-input" type="checkbox" disabled value="' . $value . '" checked="' . $checked . '"/>
                        </div>';
                }
                if(is_numeric($item[$key]) && is_float($item[$key] + 0)){
                    $item[$key] = number_format($item[$key], 2, '.', ' ');
                }
            }
            return $item;
        }, $data);
    }

    #[Route('/admin/{class}-data-load/ajax', name: 'app_load_data_ajax', methods:["GET","POST"])]
    public function ajaxLoad(Request $request, Environment $env, NativeQueryMySQL $native, LibrairieService $lib, $class): Response
    {
        $user = $this->getUser();
        $parameters = $request->getMethod() == 'GET' ? $request->query->all() : $request->request->all();
        $result = null;
        if (isset($parameters['id']) && !empty($parameters['id'])) {
            $id = $parameters['id'];
            $token = $this->renderView('_token.html.twig', ['id' => $id]);
            $result = [
                'id' => $id,
                'token' => $token,
            ];
        } else {
            $classname = 'App\\Entity\\' . ucfirst($class);
            $default_alias = "o";
            $list_columns_result = "";
            $list_join_columns_result = "";

            $elementJointure = "";
            foreach ($parameters['champs'] as $key => $field) {
                if(str_contains($field, ":")){
                    $div = explode(":", $field);
                    $elementJointure ='App\\Entity\\'. ucfirst($div[0]);
                }
            }
            dump($elementJointure);
            dd("Ok");
            

            $join_alias = [];
            $join_sql = "";
            $sql = "SELECT :columns \nFROM $classname $default_alias ";
            $count_sql = "SELECT COUNT($default_alias) \nFROM $classname $default_alias ";
            $search = (isset($parameters['search']['value'])
                && $parameters['search']['value']) ? $parameters['search']['value'] : '';
                $where ="";
                if($user->isEstAdmin()){
                    $where = "\nWHERE (o.deletedAt is null OR o.estSup != 1)";
                   
                }else{
                        if($class == "facture" || $class == "paiement"){
                            $where = "\nWHERE (o.deletedAt is null OR o.estSup != 1) ";
                        }else{
                            $where = "\nWHERE (o.deletedAt is null OR o.estSup != 1) and o.agent = ".$user->getId();
                        }
                }
           
            //$where .= "AND ";
            $where .=" AND ( ";
            $cpt = 0;
            //Gestion du chargement par ajax
            if (isset($parameters['champs']) && is_array($parameters['champs'])) {
                foreach ($parameters['champs'] as $key => $field) {
                    //Chercher la position de la sous chaîne ":$class" dans la colonne $field
                    $position = strpos($field, ":");
                    if ($position === false) {
                        $list_columns_result .= $default_alias . ".$field, ";
                        if ($search!="") {
                            $where .= $default_alias . ".$field LIKE '%$search%' OR ";
                        }
                        $this->columnsDefault[$field] = true;
                    } else {
                        $cpt++;
                        //Récupérer la position du # dans la colonne
                        $diese_index = strpos($field, "#");
                        //Récupérer la colonne réelle de la table de jointure
                        $champs = explode('#', substr($field, $diese_index + 1, strlen($field)));
                        //Récupérer le nom de la colonne de jointure
                        $join_entity_name = substr($field, 0, $position);
                        $join_table_name = substr($field, $position + 1, $diese_index - $position - 1);
                        //Récupérer le nom équivalent dans la base de donnée
                        $database_table_name = $lib->camel2dashed($join_table_name);
                        //Récupérer toutes les colonnes de la table jointure $database_table_name
                        $all_cols = $native::GetAllColumnsFromTable($database_table_name);
                        //Récupérer celles qui ne sont ni clé primaire, ni étrangère
                        $h = 0;

                        $concat = "";

                        $alias = "";

                        foreach ($all_cols as $col) {

                            if ($col['Key'] === "") {

                                $assoc_col = lcfirst($lib->camelize($col['Field']));

                                if (property_exists('App\\Entity\\' . ucfirst($join_table_name), $assoc_col) && in_array($assoc_col, $champs)) {
                                    
                                    $h++;

                                    //if($h === 1) $concat .= "CONCAT(";
                                    $concat .= $default_alias . '_' . $cpt . ".$assoc_col, ' ', ";

                                    $alias .= strtolower($assoc_col) . "_";

                                }
                            }
                            
                        }

                        $alias .= $cpt;

                        /*$concat = rtrim($concat, ", ' ' ");*/

                        $tab = explode(',', rtrim($concat, ", ' ' "));

                        if (sizeof($tab) > 1) {

                            $concat = substr_replace(rtrim($concat, ", "), "CONCAT(", 0, 0);

                            $concat = substr_replace($concat, ")", strlen($concat), 0);

                        } else
                            $concat = rtrim($concat, ", ' ' ");

                            /*$concat = rtrim($concat, ", ") . ")";*/
                        $this->columnsDefault[$alias] = true;
                        $list_join_columns_result .= $concat . " AS $alias, ";
                        $where .= "$concat LIKE '%$search%' OR ";
                        //Mettre à jour la chaîne de jointure
                        $join_sql .= "\nINNER JOIN " . $default_alias . "." . $join_entity_name . " " . $default_alias . '_' . $cpt;
                    }
                }
                $list_columns_result .= $list_join_columns_result;
                //dd($list_columns_result);
            }
            if(($env->getLoader()->exists($lib->camel2dashed($class) . '/partials/_actions.html.twig')) ) {
                $html = $this->renderView($lib->camel2dashed($class) . '/partials/_actions.html.twig', 
                [
                    'id' => '|o.id|', 
                    'class' => $class
                ]);
            }else{
                $html = $this->renderView('_actions.html.twig', ['id' => '|o.id|', 'class' => $class]);
            }
            
            $vs = explode('|', $html);
            $gs = "";
            foreach($vs as $key => $v) {
                if ($key === 0) $gs .= "CONCAT(";
                $gs .= strpos($v, "o.id") === false ? "'$v', " : $v . ', ';
            }
            $gs = rtrim($gs, ", ");
            $gs .= ")";
            $list_columns_result .= $gs . " AS action";
            $list_columns_result = rtrim($list_columns_result, ", ");
            if (strlen($where) > 6){
                $where = rtrim($where, " OR ");
                $where = rtrim($where, " AND ");
                $where .= ")";
                if (str_contains($where,'AND ()')) {
                    $where = str_replace('AND ()','', $where);
                }
            }else{
                $where = "";
            }
                
            //Reconstruire la requête avec les jointures
            $sql .= $join_sql;
            $count_sql .= $join_sql;
            $sql = str_replace(":columns", $list_columns_result, $sql) . $where . "\nORDER BY $default_alias.id DESC";
            $count_sql .= $where;
            //Récupération des données
            $alldata = $this->em->createQuery($sql)
                ->setMaxResults($parameters['length'])
                ->setFirstResult($parameters['start'])
                ->getArrayResult();
            //dd($alldata);
            // compter les données
            $totalRecords = $totalDisplay = $this->em->createQuery($count_sql)->getSingleScalarResult();
            $data = $this->reformat($alldata);
            $result = [
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalDisplay,
                'data' => $data,
            ];
        }
        //dd($parameters);
        //Retourner la réponse finale sous forme json
        return new JsonResponse($result);
    }


    #[Route('/dynamic-save-{class}/{action}/{key}/{param}', name: 'app_entity_callandsave', methods:["GET","POST"])]
    public function callAndSave(
        Request            $request,
        Environment        $env,
        LibrairieService   $lib,
        ValidatorInterface $validator,
                           $class, $action="call", 
                           $key=null, 
                           $param=null
                           ): Response
    {
        
        try {
            $data = $request->getMethod() === 'POST' ? $request->request->all() : $request->query->all();
            $html = '';
            $html_new = '';
            $form = null;
            //echo $class;
            // echo ucfirst($class);
            $classname = 'App\\Entity\\' . ucfirst($class);
            $classtype = 'App\\Form\\' . ucfirst($class . 'Type');
            $repository = $this->em->getRepository(get_class(new $classname()));
            $view = '';
            if(!empty($key)) {
                $entity = $repository->find($key);
                $message = "Modification effectuée avec succès.";
            } else {
                $entity = new $classname();
                $message = "Enregistrement effectué avec succès.";
            }
            if ($action !== 'del') {
                $reflector = new \ReflectionClass($classtype);
                $constructor = $reflector->getConstructor();
                if ($constructor && $constructor->getParameters()) {
                    //$params = $constructor->getParameters();
                    if (
                        (new $classname() instanceof Utilisateur)
                    )
                        $formtype = $reflector->newInstanceArgs([$this->em, []]);
                    else
                        $formtype = new $classtype();
                } else {
                    $formtype = new $classtype();
                }

                if ($env->getLoader()->exists($lib->camel2dashed($class) . '/partials/_card_form.html.twig')) {
                    $form = $this->createForm(get_class($formtype), $entity, [
                        'action' => $this->generateUrl('app_entity_callandsave', ['class' => $class, 'action' => 'save', 'key' => $key]),
                    ]);
                    $form->handleRequest($request);
                    $view = $lib->camel2dashed($class) . '/partials/_card_form.html.twig';
                    $html = $this->renderView($view, [
                        'entity' => $entity,
                        'form' => $form->createView(),
                        'param' => ($param) ? $param : null,
                    ]);
                }
            }
            $results = ['entity' => $entity];
            if($action === "show"){
                $entities = $repository->find($key);
                //if ($env->getLoader()->exists($lib->camel2dashed($class) . '/show.html.twig')) {
                    $view = $lib->camel2dashed($class) . '/show.html.twig';
                    $html = $this->render($view, [
                        'entitie' => $entities,
                    ]);
                //}
                $results['html'] = $html;
            }
            switch ($action) {
                case 'call':
                    break;
                // case "show":
                    
                //     break;
                case 'edit':
                    if (!empty($view)) {
                        $form_new = $this->createForm(get_class($formtype), (new $classname()), [
                            'action' => $this->generateUrl('app_entity_callandsave', ['class' => $class, 'action' => 'save', 'key' => $key]),
                        ]);
                        $form_new->handleRequest($request);
                        $html_new = $this->renderView($view, [
                            'entity' => new $classname(),
                            'form' => $form_new->createView(),
                        ]);
                        $results['edit'] = $html;
                        $results['new'] = $html_new;
                    }
                    break;
                case 'save':
                    $control = property_exists($entity, 'password') ? $form->isSubmitted() : $form->isSubmitted() && $form->isValid();
                    $errors = $validator->validate($entity);
                    //dd($form->getErrors(true, false));
                    //if (count($errors) > 0) return new JsonResponse((string)$errors);
                    if ($control) {
                        $entity->setCreatedAt(new \DateTime());
                        $entity->setUpdatedAt(new \DateTime());
                        if (property_exists($entity, 'password')) {
                            $entity->setPassword(
                                $this->passwordHasher->hashPassword(
                                    $entity,
                                    $form->get('password')->getData()
                                )
                            );
                        }
                        $repository->add($entity);
                        $results = $message;
                    }
                    break;
                case 'del':
                    $cle = null;
                    /*if ($this->isCsrfTokenValid('delete' . $entity->getId(), $data['token'])) {
                        $cle = $entity->getId();
                        $repository->remove($entity);
                    }*/
                    if ($entity->getId()) {
                        $cle = $entity->getId();
                        $entity->setEstSup(1);
                        $entity->setDeletedAt(new DateTime());
                        $repository->add($entity);
                    }
                    return new JsonResponse($cle);
                default:
            }
        } catch (\Exception $ex) {
            $results = $ex->getMessage();
        }
        return new JsonResponse($results);
    }

    #[Route('/list-des-{class}/{action}', name: 'app_entity_liste', methods:["GET"])]
    public function lists(Request $request, Environment $env, LibrairieService $lib, $class, $action)
    {
        $data = $request->query->all();
        $html = '';
        $html_new = '';

        $classname = 'App\\Entity\\' . ucfirst($class);
        $classtype = 'App\\Form\\' . ucfirst($class . 'Type');
        $repository = $this->em->getRepository(get_class(new $classname()));
        $entities = $repository->findAll();

        $entity = new $classname();
        if ($action == 'list') {
            if ($env->getLoader()->exists($lib->camel2dashed($class) . '/list.html.twig')) {
                $view = $lib->camel2dashed($class) . '/list.html.twig';
                $html = $this->renderView($view, [
                    'entities' => $entities,
                ]);
            }
            $results['html'] = $html;
        } else {
            if ($env->getLoader()->exists($lib->camel2dashed($class) . '/partials/_form.html.twig')) {
                $form = $this->createForm(get_class(new $classtype()), $entity);
                $form->handleRequest($request);
                
                $view = $lib->camel2dashed($class) . '/partials/_card_form.html.twig';
                $html_new = $this->renderView($view, [
                    'entity' => $entity,
                    'form' => $form->createView(),
                ]);
            }
            $results['html'] = $html_new;

        }
        return new JsonResponse($results);
    }
}

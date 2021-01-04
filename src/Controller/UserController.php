<?php


namespace App\Controller;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route ("/user/create", methods={"POST","GET"}, name="create")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $em)
    {
//        if ($request->isMethod('POST')){
//            $data = $request->request->all();
//            $user = new User();
//            $user->setEmail($data['email']);
//            $user->setName($data['name']);
//            $user->setPassword($data["password"]);
//
//            $em->persist($user);
//            $em->flush();
//        }
//        $form = $this->createForm(USER::class,$user , [
//            'action' => $this->generateUrl('create'),
//            'method' => 'POST',
//            'class' =>'row',
//        ]);
        $form = $this->createFormBuilder()
             ->add("name", TextType::class)
             ->add('username', TextType::class)
             ->add('email',TextType::class )
             ->add('password',PasswordType::class)
             ->add("confimer Mot de passe", PasswordType::class)
             ->getForm()
        ;
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {

        }

        return $this->render('/',['form' =>$form->createView()]);
    }

    /**
     * @Route ("/login", methods={"POST","GET"}, name="login")
     */
    public function login(): Response
    {
        return $this->render('pages/login.html.twig');
    }

    /**
     * @Route ("/register", methods={"POST","GET"}, name="register")
     */
    public function register():Response
    {
        return $this->render("pages/register.html.twig");
    }
}
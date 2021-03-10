<?php
namespace App\Controller;

use App\Entity\Form\addAuthor;
use App\Entity\Form\addBook;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Idauthorbook;
use App\Entity\mainview;
use Doctrine\ORM\EntityManagerInterface;



class DefaultController extends AbstractController
{
 /**
  * @Route("/")
  */
    public function index()
    {
        //return new Response('Hello!');
        return $this->render('base.html.twig');
    }

 /**
  * @Route("/addAuthor", name="addAuthor")
  */
    public function addAuthor(Request $request)
    {
        $author = new addAuthor();
        
        $form = $this->createFormBuilder($author)
            ->add('nameAuthor', TextType::class, array('label' => 'Имя'))
            ->add('surnameAuthor', TextType::class, array('label' => 'Фамилия'))
            ->add('Comment', TextareaType::class, array('label' => 'Комментарий (необязательно)', 'required' => false))
            ->add('save', SubmitType::class, array('label' => 'Добавить'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values (Забираем введённые юзером данные)
            // но первоначальная переменная `$task` тоже была обновлена
            $author = $form->getData();
    
            // ... . выполните действия, такие как сохранение задачи в базе данных
            // например, если Task является сущностью Doctrine, сохраните его!
            $entityManager = $this->getDoctrine()->getManager();
            $authorNew = new Author();
            $authorNew->setNameauthor($author->getnameAuthor());
            $authorNew->setSurnameauthor($author->getsurnameAuthor());
            $authorNew->setComment($author->getComment());
            $entityManager->persist($authorNew);
            $entityManager->flush();
    
            return $this->redirectToRoute('addAuthor', ['status' => 'success']);
        }
        
        return $this->render('addAuthorForm.html.twig', array(
            'form' => $form->createView(),
        ));
    }

 /**
  * @Route("/addBook", name="addBook")
  */
  public function addBook(Request $request): Response
  {
    $allAuthors = $this->getDoctrine()
    ->getRepository(author::class)
    ->findAll();
    $allAuthorsForFormBuilding=[];// массив фамилия+имя -- ид
    foreach ($allAuthors as $value) {
      $allAuthorsForFormBuilding=$allAuthorsForFormBuilding+array($value->getSurnameauthor()." ".$value->getNameauthor() => $value->getIdauthor());
    }
    ksort($allAuthorsForFormBuilding);// сортируем авторов по алфавиту
    
    $book = new addBook();
            
      $form = $this->createFormBuilder($book)
        ->add('idAuthor', ChoiceType::class,  array('choices'  =>$allAuthorsForFormBuilding,
          'multiple' => 'true', //can choose few authors. array is returned
          'label' => 'Имя Автора (можно выбрать нескольких авторов (нажмите и удерживайте кнопку ctrl))'))
        ->add('nameBook', TextType::class, array('label' => 'Название книги'))
        ->add('year', DateType::class, array('label' => 'Год выпуска','widget' => 'single_text','format' => 'yyyy'))
        ->add('Comment', TextareaType::class, array('label' => 'Комментарий (необязательно)', 'required' => false))
        ->add('bookcover', FileType::class, array('label' => 'Загрузить обложку книги', 'required' => false))
        //->add('imagelink', TextType::class, array('label' => 'Загрузить обложку', 'required' => false))
        ->add('save', SubmitType::class, array('label' => 'Добавить'))
        ->getForm();

      $form->handleRequest($request);


      if ($form->isSubmitted() && $form->isValid()) {
        // комментарии см. в форме добавления автора
        $book = $form->getData();
        $file = $form['bookcover']->getData(); // работа с файлом
        $file->move('uploads/bookcover', $file->getClientOriginalName());//!!!Тут нужно добавить алгоритм создания уникального имени
        //работа с базой
        $idAuthors=$book->getidAuthor();
        $entityManager = $this->getDoctrine()->getManager();
        $bookNew = new Book();
        $bookNew->setNamebook($book->getnameBook());
        $bookNew->setYear($book->getyear());
        $bookNew->setComment($book->getComment());
        $bookNew->setimagelink($file->getClientOriginalName());
        $entityManager->persist($bookNew);
        $entityManager->flush();
        foreach ($idAuthors as $value) {
          $idauthorbookNew= new Idauthorbook();
          $idauthorbookNew->setIdbook($bookNew);
          $author = $this->getDoctrine()->getRepository(Author::class)->find($value);
          $idauthorbookNew->setIdauthor($author);
          $entityManager->persist($idauthorbookNew);
          $entityManager->flush();
        }
        return $this->redirectToRoute('addBook', ['status' => 'success']);
    }
    return $this->render('addBookForm.html.twig', array(
      'form' => $form->createView()
    ));
  }


  /**
  * @Route("/editBook", name="editBook")
  */
  public function editBook(Request $request): Response
  {
    $allBooks=$this->getDoctrine()
    ->getRepository(mainview::class)
    ->findAll();

    var_dump($allBooks);
    
    
    
    
    
    $allAuthors = $this->getDoctrine()
    ->getRepository(author::class)
    ->findAll();
    $allAuthorsForFormBuilding=[];// массив фамилия+имя -- ид
    foreach ($allAuthors as $value) {
      $allAuthorsForFormBuilding=$allAuthorsForFormBuilding+array($value->getSurnameauthor()." ".$value->getNameauthor() => $value->getIdauthor());
    }
    ksort($allAuthorsForFormBuilding);// сортируем авторов по алфавиту
    
    $book = new addBook();
            
      $form = $this->createFormBuilder($book)
        ->add('idAuthor', ChoiceType::class,  array('choices'  =>$allAuthorsForFormBuilding,
          'multiple' => 'true', //can choose few authors. array is returned
          'label' => 'Имя Автора (можно выбрать нескольких авторов (нажмите и удерживайте кнопку ctrl))'))
        ->add('nameBook', TextType::class, array('label' => 'Название книги'))
        ->add('year', DateType::class, array('label' => 'Год выпуска','widget' => 'single_text','format' => 'yyyy'))
        ->add('Comment', TextareaType::class, array('label' => 'Комментарий (необязательно)', 'required' => false))
        ->add('bookcover', FileType::class, array('label' => 'Загрузить обложку книги', 'required' => false))
        //->add('imagelink', TextType::class, array('label' => 'Загрузить обложку', 'required' => false))
        ->add('save', SubmitType::class, array('label' => 'Добавить'))
        ->getForm();

      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        // комментарии см. в форме добавления автора
        $book = $form->getData();
        $file = $form['bookcover']->getData(); // работа с файлом
        $file->move('uploads/bookcover', $file->getClientOriginalName());
        //работа с базой
        $idAuthors=$book->getidAuthor();
        $entityManager = $this->getDoctrine()->getManager();
        $bookNew = new Book();
        $bookNew->setNamebook($book->getnameBook());
        $bookNew->setYear($book->getyear());
        $bookNew->setComment($book->getComment());
        $bookNew->setimagelink($file->getClientOriginalName());
        $entityManager->persist($bookNew);
        $entityManager->flush();
        foreach ($idAuthors as $value) {
          $idauthorbookNew= new Idauthorbook();
          $idauthorbookNew->setIdbook($bookNew);
          $author = $this->getDoctrine()->getRepository(Author::class)->find($value);
          $idauthorbookNew->setIdauthor($author);
          $entityManager->persist($idauthorbookNew);
          $entityManager->flush();
        }
        return $this->redirectToRoute('editBook', ['status' => 'success']);
    }
    //return $this->render('editBookForm.html.twig', array(
    //  'form' => $form->createView()
    //));

  }
 
}
?>
<?php
namespace App\Controller;

use App\Entity\Form\addAuthor;
use App\Entity\Form\addBook;
use App\Entity\Form\chooseEditBook;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
//use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;//
use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Idauthorbook;
use App\Entity\mainview;
use Doctrine\ORM\EntityManagerInterface;



class DefaultController extends AbstractController
{
/**
* @Route("/", name="mainPage")
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
* @Route("/removeAuthor", name="removeAuthor")
*/
public function removeAuthor(Request $request): Response
{
  $allAuthors=$this->getDoctrine()
  ->getRepository(Author::class)
  ->findAll();
  $allAuthorsList=[];
  foreach ($allAuthors as $value){
    $newelement= $value->getSurnameauthor()." ".$value->getNameauthor();
    $allAuthorsList= $allAuthorsList+array( $newelement => $value->getIdauthor());
  }
  ksort($allAuthorsList);// сортируем авторов по алфавиту
  $chooseAuthor=new chooseEditBook;// используе тот же шаблон формы, что и для книги. Поэтому в названии переменной Book а не Author
  $authorlist=$this->createFormBuilder($chooseAuthor)
          ->add('idBook', ChoiceType::class,  array('choices'  =>$allAuthorsList,
          'label' => 'Выберите автора для удаления '))
          ->add('save', SubmitType::class, array('label' => 'Удалить'))
          ->getForm();
  $authorlist->handleRequest($request);
  if ($authorlist->isSubmitted()) {
    $author = $authorlist->getData();
    $chosenId=$author->getidBook();
    $entityManager = $this->getDoctrine()->getManager();
    $authorForRemoving = $entityManager->getRepository(Author::class)->find($chosenId);
    $entityManager->remove($authorForRemoving);
    $entityManager->flush();
    return $this->redirectToRoute('removeAuthor', ['status' => 'success']);  
  }
  return $this->render('editBookFormChoose.html.twig', array(
    'booklist' => $authorlist->createView() //booklist следует читать как authorlist (см. коммент выше)
  ));
}

/**
* @Route("/editAuthor", name="editAuthor")
*/
public function editAuthor(Request $request): Response
{
  $allAuthors=$this->getDoctrine()
  ->getRepository(Author::class)
  ->findAll();
  $allAuthorsList=[];
  foreach ($allAuthors as $value){
    $newelement= $value->getSurnameauthor()." ".$value->getNameauthor();
    $allAuthorsList= $allAuthorsList+array( $newelement => $value->getIdauthor());
  }
  ksort($allAuthorsList);// сортируем авторов по алфавиту
  $chooseAuthor=new chooseEditBook;// используе тот же шаблон формы, что и для книги. Поэтому в названии переменной Book а не Author
  $authorlist=$this->createFormBuilder($chooseAuthor)
          ->add('idBook', ChoiceType::class,  array('choices'  =>$allAuthorsList,
          'label' => 'Выберите автора для редактирования'))
          ->add('save', SubmitType::class, array('label' => 'Редактировать'))
          ->getForm();
  $authorlist->handleRequest($request);
  if ($authorlist->isSubmitted()) {
    $author = $authorlist->getData();
    $chosenId=$author->getidBook();
    //(!!) тут нестандартный переход. Забираем значение и перенаправляем. Лучше эту операцию в будущем перенести на сторону клиента.
    return $this->redirectToRoute('editAuthor_Id', ["authorId"=>$chosenId]);
  }
  return $this->render('editBookFormChoose.html.twig', array(
    'booklist' => $authorlist->createView()
  ));
}

/**
* @Route("/editAuthor/{authorId}", name="editAuthor_Id")
*/
public function editAuthorId(Request $request, $authorId): Response
{
  $entityManager = $this->getDoctrine()->getManager();
  $Author=$this->getDoctrine()
  ->getRepository(Author::class)
  ->find($authorId);
 
  $author = new addAuthor();
      
  $form = $this->createFormBuilder($author)
      ->add('nameAuthor', TextType::class, array('label' => 'Имя', 'data' => $Author->getNameauthor()))
      ->add('surnameAuthor', TextType::class, array('label' => 'Фамилия', 'data' => $Author->getSurnameauthor()))
      ->add('Comment', TextareaType::class, array('label' => 'Комментарий (необязательно)',
      'data' => $Author->getComment(), 'required' => false))
      ->add('save', SubmitType::class, array('label' => 'Редактировать'))
      ->getForm();

  $form->handleRequest($request);

  if ($form->isSubmitted() && $form->isValid()) {
      $author = $form->getData();
      $Author->setNameauthor($author->getnameAuthor());
      $Author->setSurnameauthor($author->getsurnameAuthor());
      $Author->setComment($author->getComment());
      $entityManager->flush();
      return $this->redirectToRoute('editAuthor', ['status' => 'success']);
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
      ->add('year', NumberType::class, array('label' => 'Год выпуска', 'scale'=>0))
      ->add('Comment', TextareaType::class, array('label' => 'Комментарий (необязательно)', 'required' => false))
      ->add('bookcover', FileType::class, array('label' => 'Загрузить обложку книги', 'required' => false))
      ->add('save', SubmitType::class, array('label' => 'Добавить'))
      ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      // комментарии см. в форме добавления автора
      $book = $form->getData();
      $file = $form['bookcover']->getData(); // работа с файлом
      if ($file != NULL){
        $file->move('uploads/bookcover', $file->getClientOriginalName());//!!!Тут нужно добавить алгоритм создания уникального имени
      }
      //работа с базой
      $idAuthors=$book->getidAuthor();
      $entityManager = $this->getDoctrine()->getManager();
      $bookNew = new Book();
      $bookNew->setNamebook($book->getnameBook());
      $bookNew->setYear($book->getyear());
      $bookNew->setComment($book->getComment());
      if ($file != NULL){
        $bookNew->setimagelink($file->getClientOriginalName());
      }
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
* @Route("/removeBook", name="removeBook")
*/
public function removeBook(Request $request): Response
{
  $allBooks=$this->getDoctrine()
  ->getRepository(mainview::class)
  ->findAll();
  $allBooksList=[];
  foreach ($allBooks as $value){
    $newelement= $value->getNamebook().", ".$value->getAuthors().", ".$value->getYear();
    $allBooksList= $allBooksList+array( $newelement => $value->getIdbook());
  }
  ksort($allBooksList);// сортируем книги по алфавиту
  $chooseBook=new chooseEditBook;
  $booklist=$this->createFormBuilder($chooseBook)
          ->add('idBook', ChoiceType::class,  array('choices'  =>$allBooksList,
          'label' => 'Выберите книгу, которую необходимо удалить'))
          ->add('save', SubmitType::class, array('label' => 'Удалить'))
          ->getForm();
  
        $booklist->handleRequest($request);

    if ($booklist->isSubmitted()) {
      $book = $booklist->getData();
      $chosenId=$book->getidBook();
      $entityManager = $this->getDoctrine()->getManager();
      $book = $entityManager->getRepository(Book::class)->find($chosenId);
      $entityManager->remove($book);
      $entityManager->flush();
      return $this->redirectToRoute('removeBook', ['status' => 'success']);
  }
  return $this->render('editBookFormChoose.html.twig', array(
    'booklist' => $booklist->createView()
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
  $allBooksList=[];
  foreach ($allBooks as $value){
    $newelement= $value->getNamebook().", ".$value->getAuthors().", ".$value->getYear();
    $allBooksList= $allBooksList+array( $newelement => $value->getIdbook());
  }
  ksort($allBooksList);// сортируем книги по алфавиту
  $chooseBook=new chooseEditBook;
  $booklist=$this->createFormBuilder($chooseBook)
          ->add('idBook', ChoiceType::class,  array('choices'  =>$allBooksList,
          'label' => 'Выберите книгу для редактирования'))
          ->add('save', SubmitType::class, array('label' => 'Редактировать'))
          ->getForm();
  $booklist->handleRequest($request);
  if ($booklist->isSubmitted()) {
    $book = $booklist->getData();
    $chosenId=$book->getidBook();
    //(!!) тут нестандартный переход. Забираем значение и перенаправляем. Лучше эту операцию в будущем перенести на сторону клиента.
    return $this->redirectToRoute('editBook_Id', ["bookId"=>$chosenId]);
  }
  return $this->render('editBookFormChoose.html.twig', array(
    'booklist' => $booklist->createView()
  ));
}

/**
* @Route("/editBook/{bookId}", name="editBook_Id")
*/
public function editBookId(Request $request, $bookId): Response
{
  $entityManager = $this->getDoctrine()->getManager();
  $bookExemplar=$this->getDoctrine()
  ->getRepository(Book::class)
  ->find($bookId);
  $bookExemplarAuthors=$this->getDoctrine()
  ->getRepository(Idauthorbook::class)
  ->findBy( ['idbook' => $bookId]);  // возвращает массив объектов idauthorbook (см ниже:)
  //$bookExemplarAuthors[0]->getIdauthor()->getIdauthor());
  //объектов idauthorbook -- > возвращает объект автор -- > возвращает ИД для предыдущего объекта АВТОР
  $bookExemplarAuthorsIds=[];
  foreach($bookExemplarAuthors as $id){
    $bookExemplarAuthorsIds[]=$id->getIdauthor()->getIdauthor();
  }

  $allAuthors = $this->getDoctrine()
  ->getRepository(author::class)
  ->findAll();
  $allAuthorsForFormBuilding=[];// массив фамилия+имя -- ид
  foreach ($allAuthors as $value) {
    $allAuthorsForFormBuilding=$allAuthorsForFormBuilding+array($value->getSurnameauthor()." ".$value->getNameauthor() => $value->getIdauthor());
  }
  ksort($allAuthorsForFormBuilding);// сортируем авторов по алфавиту
  
  $book = new addBook();

  if ($bookExemplar->getImagelink() == NULL){
    $form = $this->createFormBuilder($book)
      ->add('idAuthor', ChoiceType::class,  array('choices'  =>$allAuthorsForFormBuilding,
        'multiple' => 'true', //can choose few authors. array is returned
        'label' => 'Имя Автора (можно выбрать нескольких авторов (нажмите и удерживайте кнопку ctrl))',
        'data'=>$bookExemplarAuthorsIds))
      ->add('nameBook', TextType::class, array('label' => 'Название книги', 'data' => $bookExemplar->getNamebook()))
      ->add('year', NumberType::class, array('label' => 'Год выпуска','scale'=>0,'data' => $bookExemplar->getYear()))
      ->add('Comment', TextareaType::class, array('label' => 'Комментарий (необязательно)', 'required' => false,
      'data' => $bookExemplar->getComment()))
      ->add('bookcover', FileType::class, array('label' => 'Загрузить обложку книги', 'required' => false))
      //если у книги нет обложки то поле пустое
      ->add('save', SubmitType::class, array('label' => 'Редактировать'))
      ->getForm();
  }else{
    $form = $this->createFormBuilder($book)
      ->add('idAuthor', ChoiceType::class,  array('choices'  =>$allAuthorsForFormBuilding,
        'multiple' => 'true', //can choose few authors. array is returned
        'label' => 'Имя Автора (можно выбрать нескольких авторов (нажмите и удерживайте кнопку ctrl))',
        'data'=>$bookExemplarAuthorsIds))
      ->add('nameBook', TextType::class, array('label' => 'Название книги', 'data' => $bookExemplar->getNamebook()))
      ->add('year', NumberType::class, array('label' => 'Год выпуска','scale'=>0,'data' => $bookExemplar->getYear()))
      ->add('Comment', TextareaType::class, array('label' => 'Комментарий (необязательно)', 'required' => false,
      'data' => $bookExemplar->getComment()))
      ->add('bookcover', FileType::class, array('label' => 'Загрузить обложку книги', 
      'data' => new File($this->getParameter('bookcovers_directory')."/".$bookExemplar->getImagelink()), 'required' => false))
      ->add('save', SubmitType::class, array('label' => 'Редактировать'))
      ->getForm();
  }
    $form->handleRequest($request);
          
    if ($form->isSubmitted() && $form->isValid()) {
      // комментарии см. в форме добавления автора
      $book = $form->getData();
      $file = $form['bookcover']->getData(); // работа с файлом
      if ($file != NULL){
        $file->move('uploads/bookcover', $file->getClientOriginalName());
      }
      //работа с базой
      $idAuthors=$book->getidAuthor();
      $bookExemplar->setNamebook($book->getnameBook());
      $bookExemplar->setYear($book->getyear());
      $bookExemplar->setComment($book->getComment());
      if ($file != NULL){
        $bookExemplar->setimagelink($file->getClientOriginalName());
      }
      $entityManager->flush();//вносим изменения в сущности
      if($idAuthors != $bookExemplarAuthorsIds){ //если произошли изменения в авторах
        foreach (array_diff($bookExemplarAuthorsIds, $idAuthors) as $value){ // удаляем авторов
          //находим ид авторов которые пользователь удалил из первоначальной формы
          $entityManager = $this->getDoctrine()->getManager();
          $idauthorbookRow=$this->getDoctrine()->getRepository(Idauthorbook::class)
          ->findOneBy( ['idbook' => $bookId, 'idauthor' => $value]);
          $entityManager->remove($idauthorbookRow);
          $entityManager->flush();
        }
        foreach (array_diff($idAuthors, $bookExemplarAuthorsIds) as $value) {  // добавляем новых авторов
          //находим ид авторов которые пользователь добавил в первоначальную форму 
          $idauthorbookNew= new Idauthorbook();
          $idauthorbookNew->setIdbook($bookExemplar);
          $author = $this->getDoctrine()->getRepository(Author::class)->find($value);
          $idauthorbookNew->setIdauthor($author);
          $entityManager->persist($idauthorbookNew);
          $entityManager->flush();
        }
      }
      return $this->redirectToRoute('editBook', ['status' => 'success']);
  }
  return $this->render('editBookForm.html.twig', array(
    'form' => $form->createView(), 'bookExemplar'=>$bookExemplar
  ));

}
 
}
?>
<?php
require __DIR__ . "/header.php";
require __DIR__ . "/book.php";

$obj = NEW book;
// вызываем ф-ю считывания всей авторов
    $authors = $obj::authorTable();

//  выводим дерево рубрик
    $headings= $obj::headingView( $obj::headingTable() );

//  Получаем массив книг
    $allBook= $obj->viewAll();

?>
	<section>
		<div class="container">
			<div class="row">
				<div class="col-sm-3">
					<div class="left-sidebar">
						<h2>Рубрики</h2>
						<div class="panel-group category-products" id="accordian"><!--category-productsr-->

                            <? echo $headings; ?>

						</div><!--/category-products-->

						<div class="brands_products"><!--brands_products-->
							<h2>Авторы</h2>
							<div class="brands-name">
								<ul class="nav nav-pills nav-stacked">
                                    <?php foreach($authors as $author):?>
									    <li><a href=""> <span class="pull-right">()</span><? echo $author['name_author']; ?></a></li>
                                    <?php endforeach;?>
									</ul>
							</div>
						</div><!--/brands_products-->

					</div>
				</div>


				<div class="col-sm-9">
					<div class="blog-post-area">
						<h2 class="title text-center">Список книг</h2>
                        <form name='publ' action='new.php' method='post'><div style='text-align: center; '><input type='image' src='/images/add.png' name='new' alt='Новая'></div></form>

                <?php foreach($allBook as $book):?>
                        <form name="book" action="edit.php" method="post">
						<div class="single-blog-post">
							<h3><? echo $book['namebook']; ?></h3>
							<div class="post-meta">
								<ul>
                                    <li><i class="fa fa-book"></i><a title='Рубрика' ><? echo $book['name_heading']; ?></a></li>
                                    <li><i class="fa fa-user"></i><a title='Авторы' ><? echo $book['name_author2']; ?></a></li>
									<li><i class="fa fa-building-o"></i><a title='Издательства' ><? echo $book['name_publishing']; ?></a></li>
									<li><i class="fa fa-calendar"></i><? echo flip_dates($book['creared'], 3); ?></li>
                                    <li><i class="fa fa-user"></i><a title='редактировать' ><input style='margin-bottom: -5px;' type='image' src='/images/edit.png' name='edit' alt='Редактировать'></a></li>
                                    <li><i class="fa fa-user"></i><a title='удалить' ><input style='margin-bottom: -5px;' type='image' src='/images/delete.png' name='delete' alt='Удалить'></a></li>
								</ul>
								<span>
										<i class="fa fa-star"></i>
										<i class="fa fa-star"></i>
										<i class="fa fa-star"></i>
										<i class="fa fa-star"></i>
										<i class="fa fa-star-half-o"></i>
								</span>
							</div>
                            <a href="/images/images/<? echo $book['photo']; ?>">
								<img src="/images/images/<? echo $book['photo']; ?>"  alt="главное изображение" >
							</a></div
                            <!-- Wrapper for slides -->
                            <div class="carousel-inner">
                                <?php $dopImages= book::dopImageBook( $book['book_id'] ); ?>
                                       <?php if( !empty ($dopImages) ): ?>

                                             <?php  $count = count($dopImages);  ?>
                                             <?php  foreach($dopImages as $img): ?>
                                                              <a href="/images/dop_images/<? echo $img; ?>"><img src="/images/dop_images/<? echo $img; ?>"  width='50px' alt="доп изображение" ></a>
                                               <?php endforeach;?>
                                       <?php endif; ?>
                            </div>

							<p class="center-block">К сожалению описание не добавлено.</p>


							<input type="hidden" name="file" value="<? echo $book['file']; ?>" >
							<input type="hidden" name="book_id" value="<? echo $book['book_id']; ?>" >
                            <input class="btn btn-primary "  type='submit' name='load_book' value='Скачать книгу'/>
                            </form>
						<hr>

                <?php endforeach;?>
                    </div>
						<div class="pagination-area">
							<ul class="pagination">
								<li><a href="" class="active">1</a></li>
								<li><a href="">2</a></li>
								<li><a href="">3</a></li>
								<li><a href=""><i class="fa fa-angle-double-right"></i></a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

<?

require __DIR__ . "/footer.php";
?>



<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Book;

class BookUserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     * @group book-store
     * @return void
     */
    public function user_can_add_book()
    {
        $this->withoutExceptionHandling();
        $response  = $this->post('/books', [
            'title'     => 'Touch me not',
            'author'    => 'Rizal'

        ]);

        $book = Book::first();
        
        $response->assertStatus(200);

        $this->assertDatabaseHas('books', [
            'title'     => $book->title,
            'author'    => $book->author
        ]);

    }

     /**
     * @test
     * @group title-require
     * @return void
     */
    public function user_requires_book_title(){
        $response = $this->post('/books', [
            'title'     => '',
            'author'    => 'Rizal'

        ]);

        $response->assertSessionHasErrors('title');
        

    }

     /**
     * @test
     * @group author-require
     * @return void
     */
    public function user_required_book_author(){
        $response = $this->post('/books', [
            'title'     => 'Touch me not',
            'author'    => ''

        ]);

        $response->assertSessionHasErrors('author');
    }

     /**
     * @test
     * @group book-delete
     * @return void
     */
    public function user_can_delete_book(){
        $this->withoutExceptionHandling();
        $this->post('/books', [
            'title'     => 'Touch me not',
            'author'    => 'Morley'

        ]);

        $book = Book::first();

        $response = $this->delete('/books/'.$book->id);
        $response->assertStatus(200);

        $this->assertDatabaseMissing('books', [
            'title'     => $book->title,
            'author'    => $book->author
        ]);

    }

    /**
     * @test
     * @group book-update
     * @return void
     */
    public function user_can_update_book(){

        $this->withoutExceptionHandling();
        $this->post('/books', [
            'title'     => 'Touch me not',
            'author'    => 'Morley'

        ]);

        $book = Book::first();
        
        $response = $this->patch('/books/'.$book->id, [
            'title'     => 'New Title',
            'author'    => 'New Author'
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('books', [
            'id'        => $book->id,
            'title'     => 'New Title',
            'author'    => 'New Author'
        ]);



    }

     /**
     * @test
     * @group book-list
     * @return void
     */
    public function user_can_view_book_list(){

        $this->withoutExceptionHandling();

       $this->post('/books', [
            'title'     => 'Touch me not',
            'author'    => 'Rizal'
        ]);
        
        $response = $this->get('/books');

        $response->assertStatus(200);

        $response->assertSee('Touch me not');
       

    }


}

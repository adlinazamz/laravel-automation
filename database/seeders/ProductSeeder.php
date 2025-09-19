<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()//: void
    {
        Product::factory()->count(50)->create();
       /** $product_seed =[
            *['id'=>'1','name'=>'The Great Gatsby','detail'=>'A novel written by American author F. Scott Fitzgerald that follows a cast of characters living in the fictional towns of West Egg and East Egg on prosperous Long Island in the summer of 1922.','image'=>'"C:\laragon\www\book_store\public\images\20250917083554.png"'],
            *['id'=>'2','name'=>'To Kill a Mockingbird','detail'=>'A novel by Harper Lee published in 1960. Instantly successful, widely read in high schools and middle schools in the United States, it has become a classic of modern American literature, winning the Pulitzer Prize.','image'=>'C:\laragon\www\book_store\public\images\20250917083554.png'],
            *['id'=>'3','name'=>'1984','detail'=>'A dystopian social science fiction novel and cautionary tale, written by the English writer George Orwell. It was published on 8 June 1949 by Secker & Warburg as Orwell\'s ninth and final book completed in his lifetime.','image'=>'"C:\laragon\www\book_store\public\images\20250917083554.png"'],
            *['id'=>'4','name'=>'Pride and Prejudice','detail'=>'A romantic novel of manners written by Jane Austen in 1813. The novel follows the character development of Elizabeth Bennet, the dynamic protagonist of the book who learns about the repercussions of hasty judgments and eventually comes to appreciate the difference between superficial goodness and actual goodness.', 'image'=>'"C:\laragon\www\book_store\public\images\20250917083554.png"'],
            *['id'=>'5','name'=>'The Catcher in the Rye','detail'=>'A novel by J. D. Salinger, partially published in serial form in 1945–1946 and as a novel in 1951. It is widely considered one of the greatest American novels of the 20th century.','image'=>'"C:\laragon\www\book_store\public\images\20250917083554.png"'],
            *['id'=>'6','name'=>'The Hobbit','detail'=>'A children\'s fantasy novel by English author J. R. R. Tolkien. It was published on 21 September 1937 to wide critical acclaim, being nominated for the Carnegie Medal and awarded a prize from the New York Herald Tribune for best juvenile fiction.','image'=>'C:\laragon\www\book_store\public\images\20250917083616.png'],
            *['id'=>'7','name'=>'Fahrenheit 451','detail'=>'A dystopian novel by American writer Ray Bradbury, published in 1953. It is regarded as one of his best works. The novel presents a future American society where books are outlawed and "firemen" burn any that are found.','image'=>'"C:\laragon\www\book_store\public\images\20250917083554.png"'],
            *['id'=>'8','name'=>'Animal Farm','detail'=>'A satirical allegorical novella by George Orwell, first published in England on 17 August 1945. According to Orwell, the book reflects events leading up to the Russian Revolution of 1917 and then on into the Stalinist era of the Soviet Union.','image'=>'"C:\laragon\www\book_store\public\images\20250917083554.png"'],
            *['id'=>'9','name'=>'alice in Wonderland','detail'=>'An 1865 novel written by English author Lewis Carroll (the pseudonym of Charles Lutwidge Dodgson) about .', 'image'=>'"C:\laragon\www\book_store\public\images\20250917083554.png"'],
            *['id'=>'10','name'=>'The Lord of the Rings','detail'=>'An epic high-fantasy novel written by English author and scholar J. R. R. Tolkien. The story began as a sequel to Tolkien\'s 1937 fantasy novel The Hobbit, but eventually developed into a much larger work.','image'=>'C:\laragon\www\book_store\public\images\20250917083616.png'],          
            *['id'=>'11','name'=>'The Great Gatsby','detail'=>'A novel written by American author F. Scott Fitzgerald that follows a cast of characters living in the fictional towns of West Egg and East Egg on prosperous Long Island in the summer of 1922.','image'=>'"C:\laragon\www\book_store\public\images\20250917083554.png"'],    
        *];
        *foreach ($product_seed as $product_seed)
        *{
        *    Product::firstOrCreate($product_seed);
        *}
            */
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Quote;
use Illuminate\Support\Facades\DB;

class QuoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel terlebih dahulu untuk menghindari duplikat jika seeder dijalankan lagi
        DB::table('quotes')->truncate();

        $quotes = [
            ["text" => "The only way to do great work is to love what you do.", "author" => "Steve Jobs"],
            ["text" => "Success is not final, failure is not fatal: it is the courage to continue that counts.", "author" => "Winston Churchill"],
            ["text" => "In the middle of difficulty lies opportunity.", "author" => "Albert Einstein"],
            ["text" => "You miss 100% of the shots you don’t take.", "author" => "Wayne Gretzky"],
            ["text" => "Don’t watch the clock; do what it does. Keep going.", "author" => "Sam Levenson"],
            ["text" => "Whether you think you can or you think you can’t, you’re right.", "author" => "Henry Ford"],
            ["text" => "It always seems impossible until it’s done.", "author" => "Nelson Mandela"],
            ["text" => "I never dreamed about success, I worked for it.", "author" => "Estée Lauder"],
            ["text" => "Do not wait for leaders; do it alone, person to person.", "author" => "Mother Teresa"],
            ["text" => "Try not to become a man of success. Rather become a man of value.", "author" => "Albert Einstein"],
            ["text" => "Success usually comes to those who are too busy to be looking for it.", "author" => "Henry David Thoreau"],
            ["text" => "Do what you can, with what you have, where you are.", "author" => "Theodore Roosevelt"],
            ["text" => "Believe you can and you're halfway there.", "author" => "Theodore Roosevelt"],
            ["text" => "Everything you can imagine is real.", "author" => "Pablo Picasso"],
            ["text" => "The future depends on what you do today.", "author" => "Mahatma Gandhi"],
            ["text" => "Act as if what you do makes a difference. It does.", "author" => "William James"],
            ["text" => "Hardships often prepare ordinary people for an extraordinary destiny.", "author" => "C.S. Lewis"],
            ["text" => "If opportunity doesn’t knock, build a door.", "author" => "Milton Berle"],
            ["text" => "The best way to predict the future is to create it.", "author" => "Peter Drucker"],
            ["text" => "You don’t have to be great to start, but you have to start to be great.", "author" => "Zig Ziglar"],
            ["text" => "Happiness is not something ready made. It comes from your own actions.", "author" => "Dalai Lama"],
            ["text" => "Strive not to be a success, but rather to be of value.", "author" => "Albert Einstein"],
            ["text" => "If you want to lift yourself up, lift up someone else.", "author" => "Booker T. Washington"],
            ["text" => "Great minds discuss ideas; average minds discuss events; small minds discuss people.", "author" => "Eleanor Roosevelt"],
            ["text" => "Your time is limited, so don’t waste it living someone else’s life.", "author" => "Steve Jobs"],
            ["text" => "Only those who dare to fail greatly can ever achieve greatly.", "author" => "Robert F. Kennedy"],
            ["text" => "Life is 10% what happens to us and 90% how we react to it.", "author" => "Charles R. Swindoll"],
            ["text" => "Go as far as you can see; when you get there, you’ll be able to see further.", "author" => "Thomas Carlyle"],
            ["text" => "Success is walking from failure to failure with no loss of enthusiasm.", "author" => "Winston Churchill"],
            ["text" => "What lies behind us and what lies before us are tiny matters compared to what lies within us.", "author" => "Ralph Waldo Emerson"],
            ["text" => "The only limit to our realization of tomorrow is our doubts of today.", "author" => "Franklin D. Roosevelt"],
            ["text" => "Don’t be pushed around by the fears in your mind. Be led by the dreams in your heart.", "author" => "Roy T. Bennett"],
            ["text" => "Shoot for the moon. Even if you miss, you'll land among the stars.", "author" => "Norman Vincent Peale"],
            ["text" => "Limit your “always” and your “nevers.”", "author" => "Amy Poehler"],
            ["text" => "What you do makes a difference, and you have to decide what kind of difference you want to make.", "author" => "Jane Goodall"],
            ["text" => "Dream big and dare to fail.", "author" => "Norman Vaughan"],
            ["text" => "Keep your face always toward the sunshine—and shadows will fall behind you.", "author" => "Walt Whitman"],
            ["text" => "Opportunities don't happen. You create them.", "author" => "Chris Grosser"],
            ["text" => "Don’t let yesterday take up too much of today.", "author" => "Will Rogers"],
            ["text" => "If you’re going through hell, keep going.", "author" => "Winston Churchill"],
            ["text" => "I find that the harder I work, the more luck I seem to have.", "author" => "Thomas Jefferson"],
            ["text" => "Success is how high you bounce when you hit bottom.", "author" => "George S. Patton"],
            ["text" => "Start where you are. Use what you have. Do what you can.", "author" => "Arthur Ashe"],
            ["text" => "Success is not how high you have climbed, but how you make a positive difference to the world.", "author" => "Roy T. Bennett"],
            ["text" => "Don’t wish it were easier; wish you were better.", "author" => "Jim Rohn"],
            ["text" => "Push yourself, because no one else is going to do it for you.", "author" => "Les Brown"],
            ["text" => "You are never too old to set another goal or to dream a new dream.", "author" => "C.S. Lewis"],
            ["text" => "If you can dream it, you can do it.", "author" => "Walt Disney"],
            ["text" => "All our dreams can come true, if we have the courage to pursue them.", "author" => "Walt Disney"],
            ["text" => "Do one thing every day that scares you.", "author" => "Eleanor Roosevelt"],
            ["text" => "Opportunities are usually disguised as hard work, so most people don’t recognize them.", "author" => "Ann Landers"],
            ["text" => "The question isn’t who is going to let me; it’s who is going to stop me.", "author" => "Ayn Rand"],
            ["text" => "Don’t limit yourself. Many people limit themselves to what they think they can do.", "author" => "Mary Kay Ash"],
            ["text" => "Success is liking yourself, liking what you do, and liking how you do it.", "author" => "Maya Angelou"],
            ["text" => "You can't build a reputation on what you are going to do.", "author" => "Henry Ford"],
            ["text" => "If you want something you never had, you have to do something you’ve never done.", "author" => "Thomas Jefferson"],
            ["text" => "When something is important enough, you do it even if the odds are not in your favor.", "author" => "Elon Musk"],
            ["text" => "I have not failed. I've just found 10,000 ways that won't work.", "author" => "Thomas A. Edison"],
            ["text" => "It's not whether you get knocked down, it's whether you get up.", "author" => "Vince Lombardi"],
            ["text" => "Success is the sum of small efforts, repeated day in and day out.", "author" => "Robert Collier"],
            ["text" => "Perseverance is not a long race; it is many short races one after the other.", "author" => "Walter Elliot"],
            ["text" => "A goal without a plan is just a wish.", "author" => "Antoine de Saint-Exupéry"],
            ["text" => "Don’t count the days, make the days count.", "author" => "Muhammad Ali"],
            ["text" => "Make each day your masterpiece.", "author" => "John Wooden"],
            ["text" => "The harder you work for something, the greater you’ll feel when you achieve it.", "author" => "Unknown"],
            ["text" => "Work hard in silence, let your success be the noise.", "author" => "Frank Ocean"],
            ["text" => "Your passion is waiting for your courage to catch up.", "author" => "Isabelle Lafleche"],
            ["text" => "Magic is believing in yourself. If you can make that happen, you can make anything happen.", "author" => "Johann Wolfgang von Goethe"],
            ["text" => "Don’t wait. The time will never be just right.", "author" => "Napoleon Hill"],
            ["text" => "Great things never come from comfort zones.", "author" => "Ginni Rometty"],
            ["text" => "Do what you love and the money will follow.", "author" => "Marsha Sinetar"],
            ["text" => "The best revenge is massive success.", "author" => "Frank Sinatra"],
            ["text" => "Small deeds done are better than great deeds planned.", "author" => "Peter Marshall"],
            ["text" => "A year from now you may wish you had started today.", "author" => "Karen Lamb"],
            ["text" => "Discipline is the bridge between goals and accomplishment.", "author" => "Jim Rohn"],
            ["text" => "Motivation gets you going, but discipline keeps you growing.", "author" => "John C. Maxwell"],
            ["text" => "The difference between ordinary and extraordinary is that little extra.", "author" => "Jimmy Johnson"],
            ["text" => "Energy and persistence conquer all things.", "author" => "Benjamin Franklin"],
            ["text" => "Diligence is the mother of good luck.", "author" => "Benjamin Franklin"],
            ["text" => "If you do what you’ve always done, you’ll get what you’ve always gotten.", "author" => "Tony Robbins"],
            ["text" => "Success is not in what you have, but who you are.", "author" => "Bo Bennett"],
            ["text" => "I am not a product of my circumstances. I am a product of my decisions.", "author" => "Stephen Covey"],
            ["text" => "Don’t be afraid to give up the good to go for the great.", "author" => "John D. Rockefeller"],
            ["text" => "If you’re not stubborn, you’ll give up on experiments too soon.", "author" => "Jeff Bezos"],
            ["text" => "The only place where success comes before work is in the dictionary.", "author" => "Vidal Sassoon"],
            ["text" => "The road to success and the road to failure are almost exactly the same.", "author" => "Colin R. Davis"],
            ["text" => "Courage is resistance to fear, mastery of fear—not absence of fear.", "author" => "Mark Twain"],
            ["text" => "The secret of success is to do the common thing uncommonly well.", "author" => "John D. Rockefeller Jr."],
            ["text" => "Fall seven times, stand up eight.", "author" => "Japanese Proverb"],
            ["text" => "Do not be embarrassed by your failures, learn from them and start again.", "author" => "Richard Branson"],
            ["text" => "You just can’t beat the person who never gives up.", "author" => "Babe Ruth"],
            ["text" => "There are no secrets to success. It is the result of preparation, hard work, and learning from failure.", "author" => "Colin Powell"],
            ["text" => "Success is getting what you want. Happiness is wanting what you get.", "author" => "Dale Carnegie"],
        ];

        // Masukkan data ke dalam database
        foreach ($quotes as $quote) {
            Quote::create($quote);
        }
    }
}

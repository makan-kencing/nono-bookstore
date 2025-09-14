<?php

declare(strict_types=1);

use App\Core\Template;



$template = new Template(
    'webstore/_base.php',
    ['title' => 'About Us | Novelty N Nonsense']
);

?>

<?php $template->start() ?>
<div class="container">
    <h1>About Us</h1>
    <div class="motto">"Bringing the novelty out of nonsense."</div>

    <div class="rustic-border">
        <p>
            Welcome to <strong>Novelty N Nonsense</strong> — a rustic little corner tucked away from the rush of modern life.
            Our bookstore isn’t just a place to buy books; it’s a home for dreamers, thinkers, and wanderers who
            find comfort in the smell of old pages and the creak of wooden floors.
        </p>
    </div>

    <h2>Our Story</h2>
    <p>
        Founded with a love for words and the whimsical nature of stories, Novelty N Nonsense began as a humble collection
        of secondhand treasures. Over time, it grew into a sanctuary for readers seeking something rare, odd, or delightfully unexpected.
        Whether it’s a leather-bound classic, a quirky children’s tale, or a forgotten gem from decades past,
        our shelves are filled with wonders waiting to be discovered.
    </p>

    <h2>Our Atmosphere</h2>
    <p>
        Step inside and you’ll be greeted by warm wooden beams, vintage armchairs, and shelves that seem to whisper stories of their own.
        The store carries the air of old rustic charm — a place where time slows down and the noise of the outside world fades away.
        It’s cozy, imperfect, and beautifully alive, just like the stories we carry.
    </p>

    <h2>Our Mission</h2>
    <p>
        At Novelty N Nonsense, our mission is simple: to <em>bring the novelty out of nonsense</em>.
        We believe in the magic of stories that might seem peculiar at first glance, but often hold the most profound truths.
        Our goal is to help readers rediscover joy, wonder, and inspiration through the unexpected.
    </p>

    <h2>Visit Us</h2>
    <p>
        Whether you’re hunting for a rare edition, searching for the perfect gift, or just craving a quiet corner to read,
        we welcome you to explore our little haven. Come in, stay awhile, and let the nonsense of the world turn into novelty.
    </p>
</div>

<style>
    body {
        font-family: Georgia, serif;
        background-color: #f4f1ec;
        color: #3a2c23;
        line-height: 1.7;
        margin: 0;
        padding: 0;
    }
    .container {
        max-width: 900px;
        margin: auto;
        padding: 2rem;
    }
    h1 {
        text-align: center;
        font-size: 2.5rem;
        margin-bottom: 1rem;
        color: #4b2e14;
    }
    h2 {
        margin-top: 2rem;
        color: #5a3e20;
    }
    p {
        margin-bottom: 1rem;
    }
    .motto {
        text-align: center;
        font-style: italic;
        margin: 2rem 0;
        font-size: 1.3rem;
        color: #7a5b3a;
    }
    .rustic-border {
        border-top: 2px solid #d2b48c;
        border-bottom: 2px solid #d2b48c;
        padding: 1rem 0;
        margin: 2rem 0;
    }
</style>
<?= $template->end() ?>

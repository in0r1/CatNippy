@extends('layouts.app')

@section('content')
<div class="container py-5">

    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold">
                    Welcome to CatNippy
                </h1>
                <p class="text-muted">
                    where a love of cats meets a passion for nature, science, and a little bit of fun!
                </p>
            </div>

            <div class="mb-5">
                <h2 class="fw-bold">How CatNippy Started</h2>

                <p class="text-muted">
                    CatNippy was founded by <b>Janusz Biały</b>, a biochemistry and fishing enthusiast from Społeczne LO IV in Poznań, Poland.
                        <br>
                    With a keen interest in the natural world and the science behind it, Janusz decided to turn his curiosity into something special: a shop dedicated to making cats' lives a little more exciting.
                    <br>
                    From aromatic dried catnip and sprays to playful toys and natural herbal alternatives, CatNippy offers a range of products designed to spark curiosity, encourage play, and bring out every cat's playful side.
                    <br>
                    For Janusz, there's a surprising connection between fishing, biochemistry, and cats: it all comes down to patience, curiosity, and understanding how nature works. Whether he's out by the water with a fishing rod or exploring the fascinating chemistry of catnip, he's always eager to learn something new.
                    <br>
                    At CatNippy, we believe that even the smallest things can bring a lot of joy, especially when there's a curious cat involved.
                </p>
            </div>

            <div class="mb-5">
                <h3 class="fw-bold">Our mission? To make cats happy, one leaf of catnip at a time.</h2>

                <p class="text-muted">
                    CatNippy – Powered by Nature, Inspired by Science, Approved by Cats.
                </p>
            </div>

            <div class="text-center py-4">
                <h3 class="fw-bold">Thanks for being part of our story.</h3>
                <a href="{{ route('products.index') }}"
                   class="btn btn-dark mt-2">
                    Explore the Shop
                </a>
            </div>

        </div>
    </div>

</div>
@endsection
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Loto - 10x Plus de Chances</title>
        <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Raleway:ital,wght@0,300;1,300&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="Content/style.css">
    </head>
    
<body>
    <header class="hero">
        <nav>
            <ul class="nav-bar">
                <li><a href="page_creation.html">Ajouter un pseudo</a></li>
                <li><a href="#simulation">Simulation</a></li>
            </ul>
        </nav>
        <div class="hero-text">
            <h1>
                <span class="big-text">10X</span><br>
                <span class="small-text">Chances de gagner</span>
            </h1>
        </div>
        
    </header>

    <section class="rules">
        <div class="rules-container">
            <div class="rules-box">
                <h2>Règles du jeu</h2>
                <p>
                    Bienvenue dans le loto où tu as 10 fois plus de chances de gagner. 
                    Voici les règles : chaque participant choisit 5 numéros entre 1 et 49. 
                    Tirage au sort chaque semaine. Jouer est strictement interdit aux personnes de moins de 18 ans.
                </p>
            </div>
            <div class="image-box">
                <img src="Contetn/loto.webp" alt="Loto Image">
            </div>
        </div>
    </section>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const rulesSection = document.querySelector('.rules');
    
            function isVisible(element) {
                const rect = element.getBoundingClientRect();
                const windowHeight = window.innerHeight || document.documentElement.clientHeight;
    
                return rect.top <= windowHeight - 100 && rect.bottom >= 0;
            }
    
            function handleScroll() {
                if (isVisible(rulesSection)) {
                    rulesSection.classList.add('visible'); // Ajoute la classe pour rendre visible
                } else {
                    rulesSection.classList.remove('visible'); // Réinitialise quand la section sort du viewport
                }
            }
    
            window.addEventListener('scroll', handleScroll); // Écoute l'événement de scroll
        });
    </script>
    
    
    
</body>
</html>

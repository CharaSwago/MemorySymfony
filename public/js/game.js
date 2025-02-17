let deck = [];
let hasFlippedCard = false;
let lockBoard = false;
let firstCard, secondCard;
let pairsFound = 0;
let timerInterval;
let isTimerRunning = false;
let isGameStarted = false;
let elapsedTime = 0;
let startTime;
let chrono;
var totalPairs;

document.addEventListener('DOMContentLoaded', function() {
    deck = document.querySelectorAll('.memory-card');
    totalPairs = deck.length / 2;
    chrono = document.getElementById('chrono');
    const start = document.getElementById('startButton');
    const reset = document.getElementById('resetButton');
    console.log('Cartes sélectionnées : ', deck.length);    // Mélanger les cartes au chargement
    // Mélanger les cartes au chargement
    shuffle();
    start.onclick = function() {
        startTimer();
        console.log('Bouton Start cliqué');
    };

    reset.onclick = function() {
        resetGame();
        console.log('Timer remis à zéro');
    };

    deck.forEach(card => {
        card.addEventListener('click', flipCard);
        console.log('Événement de clic ajouté à la carte : ', card.dataset.framework);
    });
});

// Fonction pour démarrer le timer
function startTimer() {
    if (!isTimerRunning) {
        isGameStarted = true;
        startTime = Date.now();
        timerInterval = setInterval(updateTimer, 1000); // Met à jour chaque seconde
        isTimerRunning = true;
        console.log("Timer démarré");
        console.log("nombre de paires : " + totalPairs)
    }
}

// Fonction pour mettre à jour le timer et l'afficher
function updateTimer() {
    elapsedTime = Math.floor((Date.now() - startTime) / 1000);
    let hrs = Math.floor(elapsedTime / 3600);
    let min = Math.floor((elapsedTime % 3600) / 60);
    let sec = elapsedTime % 60;

    chrono.textContent = (hrs > 9 ? hrs : '0' + hrs) + ':' +
                    (min > 9 ? min : '0' + min) + ':' +
                    (sec > 9 ? sec : '0' + sec);
}

// Fonction pour arrêter le timer
function stopTimer() {
    clearInterval(timerInterval);
    isTimerRunning = false;
}

// Fonction pour réinitialiser le timer
function resetTimer() {
    clearInterval(timerInterval);
    elapsedTime = 0;
    chrono.textContent = '00:00:00';
    isTimerRunning = false;
    isGameStarted = false;
}

// Fonction pour retourner une carte
function flipCard() {
    console.log("Carte cliquée");
    if (!isGameStarted || lockBoard || this.classList.contains('flip')) return;

    this.classList.add('flip');
    console.log("Carte retournée : ", this.dataset.framework);

    if (!hasFlippedCard) {
        hasFlippedCard = true;
        firstCard = this;
        console.log("Première carte : ", firstCard.dataset.framework);
        return;
    }

    secondCard = this;
    console.log("Seconde carte : ", secondCard.dataset.framework);
    checkForMatch();
}

// Vérifie si les deux cartes retournées sont identiques
function checkForMatch() {
    let isMatch = firstCard.dataset.framework === secondCard.dataset.framework;

    if (isMatch) {
        disableCards();
        pairsFound++;
        console.log("Paire trouvée, bravo !")
        if (pairsFound === totalPairs) {
            stopTimer();
            alert("Félicitations ! Vous avez terminé en " + chrono.textContent);
            sendScore(elapsedTime); // Envoyer le score
        }
    } else {
        unflipCards();
    }
}

// Désactiver les cartes si elles correspondent
function disableCards() {
    firstCard.removeEventListener('click', flipCard);
    secondCard.removeEventListener('click', flipCard);
    resetBoard();
}

// Retourner les cartes si elles ne correspondent pas
function unflipCards() {
    lockBoard = true;

    setTimeout(() => {
        firstCard.classList.remove('flip');
        secondCard.classList.remove('flip');
        resetBoard();
    }, 1500);
}

// Réinitialiser les variables du jeu
function resetBoard() {
    [hasFlippedCard, lockBoard] = [false, false];
    [firstCard, secondCard] = [null, null];
}

function shuffle() {
    const shuffledDeck = Array.from(deck);
    for (let i = shuffledDeck.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [shuffledDeck[i], shuffledDeck[j]] = [shuffledDeck[j], shuffledDeck[i]]; // Échange les éléments
    }
    
    shuffledDeck.forEach((card, index) => {
        card.style.order = index; // Appliquer l'ordre mélangé
    });
}

// Mélanger les cartes au chargement
shuffle();

// Envoyer le score à l'API Symfony

function sendScore(score) {
    const level = 1;
    fetch('/score/submit', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({ score: score, difficulty: level })
    })
    .then(response => {
        // Check if response is actually JSON
        if (!response.ok) {
            return response.text().then(text => { throw new Error(text); });
        }
        return response.json();
    })
    .then(data => {
        if (data.status === 'success') {
            alert("Votre score a été enregistré : " + score + " secondes !");
        } else {
            alert("Erreur : " + data.message);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert("An error occurred: " + error.message);
    });
}


// Réinitialiser le jeu
function resetGame() {
    resetTimer();
    pairsFound = 0;
    deck.forEach(card => card.classList.remove('flip'));
    shuffle();
    resetBoard();
}

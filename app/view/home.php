<?php defined( 'BASE' ) or die( 'No Direct Access!' ); ?>
<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1>What's Inside?</h1>
            <p>Complete a simple offer and discover something unexpected. No promises, no guarantees—just pure mystery waiting to be uncovered.</p>
            <button class="mystery-btn" id="openOfferwall" onclick="ACTPRO.offerwall()">Reveal the Mystery</button>
        </div>
    </div>
    
    <!-- Floating Elements -->
    <div class="floating floating-1">?</div>
    <div class="floating floating-2">?</div>
</section>

<!-- Mystery Box Section -->
<section class="container">
    <div class="mystery-box">
        <h2>Go Ahead, Click It - We Dare You</h2>
        <p>Thousands have clicked. Some were surprised. Others were shocked. A few even screamed (in a good way). What will your experience be?</p>
        <button class="mystery-btn" id="openOfferwall2" onclick="ACTPRO.offerwall()">I Dare You</button>
    </div>
</section>
<script>
    // ======= SURPRISE FUNCTION ======= //
    function triggerSurprise() {
        const surprises = [
            // Visual Effects
            () => {
                // Confetti explosion with question marks
                for (let i = 0; i < 50; i++) {
                    const confetti = document.createElement('div');
                    confetti.innerHTML = ['?', '!', '...', '¿'][Math.floor(Math.random() * 4)];
                    confetti.style.position = 'fixed';
                    confetti.style.left = Math.random() * window.innerWidth + 'px';
                    confetti.style.top = '-20px';
                    confetti.style.fontSize = (Math.random() * 20 + 10) + 'px';
                    confetti.style.color = ['#6c5ce7', '#fd79a8', '#00cec9', '#ffffff'][Math.floor(Math.random() * 4)];
                    confetti.style.zIndex = '1000';
                    confetti.style.animation = `fall ${Math.random() * 3 + 2}s linear forwards`;
                    document.body.appendChild(confetti);
                    
                    setTimeout(() => confetti.remove(), 3000);
                }
                return "Did you see that?";
            },
            
            // Mysterious Sound
            () => {
                const sounds = [
                    '<?php echo Url::baseUrl() ?>/app/view/effect/1.wav',
                    '<?php echo Url::baseUrl() ?>/app/view/effect/2.wav',
                    '<?php echo Url::baseUrl() ?>/app/view/effect/3.wav',
                    '<?php echo Url::baseUrl() ?>/app/view/effect/4.wav',
                    '<?php echo Url::baseUrl() ?>/app/view/effect/5.wav',
                    '<?php echo Url::baseUrl() ?>/app/view/effect/6.wav',
                    '<?php echo Url::baseUrl() ?>/app/view/effect/7.wav',
                ];
                const audio = new Audio(sounds[Math.floor(Math.random() * sounds.length)]);
                audio.volume = 0.3;
                audio.play();
                return "Did you hear something?";
            },
            
            // Fake Glitch Effect
            () => {
                document.body.style.transform = 'translateX(5px)';
                setTimeout(() => {
                    document.body.style.transform = 'translateX(-5px)';
                    setTimeout(() => document.body.style.transform = '', 100);
                }, 100);
                return "Wait... did the screen just glitch?";
            },
            
            // Cryptic Message
            () => {
                const messages = [
                    "You're being watched...",
                    "The offerwall knows...",
                    "3...2...1... surprise!",
                    "This wasn't here before..."
                ];
                const msg = document.createElement('div');
                msg.textContent = messages[Math.floor(Math.random() * messages.length)];
                msg.style.position = 'fixed';
                msg.style.bottom = '20px';
                msg.style.right = '20px';
                msg.style.background = 'rgba(0,0,0,0.7)';
                msg.style.color = '#fd79a8';
                msg.style.padding = '10px 20px';
                msg.style.borderRadius = '50px';
                msg.style.zIndex = '1000';
                document.body.appendChild(msg);
                setTimeout(() => msg.remove(), 2000);
                return "Did you catch that?";
            },
            
            // Fake Loading Screen
            () => {
                const loader = document.createElement('div');
                loader.innerHTML = `
                    <div style="position:fixed; top:0; left:0; width:100%; height:100%; 
                                background:#0f0f1a; z-index:9999; display:flex; 
                                flex-direction:column; align-items:center; justify-content:center;">
                        <div style="font-size:24px; margin-bottom:20px;">Decrypting message...</div>
                        <div style="width:200px; height:4px; background:#333; border-radius:2px;">
                            <div id="progress" style="height:100%; width:0%; background:#6c5ce7; border-radius:2px;"></div>
                        </div>
                    </div>
                `;
                document.body.appendChild(loader);
                
                let progress = 0;
                const interval = setInterval(() => {
                    progress += Math.random() * 20;
                    document.getElementById('progress').style.width = `${Math.min(progress, 100)}%`;
                    if (progress >= 100) {
                        clearInterval(interval);
                        setTimeout(() => loader.remove(), 500);
                    }
                }, 200);
                
                return "Loading something special...";
            }
        ];
        
        // Trigger a random surprise
        const randomSurprise = surprises[Math.floor(Math.random() * surprises.length)]();
        console.log(randomSurprise); // For debugging
    }

    // Add confetti effect on button hover
    const mysteryBtns = document.querySelectorAll('.mystery-btn');
    mysteryBtns.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            // Simple "confetti" effect using question marks
            for (let i = 0; i < 10; i++) {
                const confetti = document.createElement('div');
                confetti.innerHTML = '?';
                confetti.style.position = 'fixed';
                confetti.style.left = Math.random() * window.innerWidth + 'px';
                confetti.style.top = '-20px';
                confetti.style.fontSize = (Math.random() * 20 + 10) + 'px';
                confetti.style.opacity = Math.random();
                confetti.style.animation = 'fall ' + (Math.random() * 3 + 2) + 's linear forwards';
                document.body.appendChild(confetti);
                
                // Remove after animation
                setTimeout(() => {
                    confetti.remove();
                }, 3000);
            }
        });
    });

    // Add falling animation
    const style = document.createElement('style');
    style.innerHTML = `
        @keyframes fall {
            to {
                transform: translateY(calc(100vh + 20px)) rotate(360deg);
            }
        }
    `;
    document.head.appendChild(style);
</script>
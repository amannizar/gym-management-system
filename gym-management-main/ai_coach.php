<?php
require_once "backend/member_nav.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mars AI Coach 🪐</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        :root {
            --glass-bg: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
            --bot-msg-bg: rgba(0, 255, 163, 0.15);
            --user-msg-bg: rgba(255, 255, 255, 0.1);
            --accent-glow: 0 0 15px rgba(0, 255, 163, 0.3);
        }

        body {
            font-family: 'Outfit', sans-serif;
            overflow: hidden; /* Prevent double scrollbars */
        }

        .video-bg {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            object-fit: cover; z-index: -2;
        }
        .video-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(10, 10, 10, 0.85); 
            z-index: -1; 
            backdrop-filter: blur(10px);
        }

        .chat-wrapper {
            height: calc(100vh - 100px); /* Adjust for navbar */
            max-width: 900px;
            margin: 20px auto;
            display: flex;
            flex-direction: column;
        }

        .chat-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            position: relative;
        }

        .chat-header {
            padding: 20px;
            border-bottom: 1px solid var(--glass-border);
            background: rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .mars-avatar {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #00ffa3, #00afff);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: var(--accent-glow);
            border: 2px solid rgba(255,255,255,0.2);
        }

        .chat-body {
            flex: 1;
            overflow-y: auto;
            padding: 24px;
            scroll-behavior: smooth;
        }
        
        /* Scrollbar styling */
        .chat-body::-webkit-scrollbar { width: 6px; }
        .chat-body::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }

        .message {
            margin-bottom: 20px;
            max-width: 80%;
            padding: 16px 20px;
            border-radius: 18px;
            font-size: 1rem;
            line-height: 1.5;
            position: relative;
            animation: fadeIn 0.3s ease;
        }
        
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        .bot {
            background: var(--bot-msg-bg);
            color: #e0fff4;
            border-bottom-left-radius: 4px;
            align-self: flex-start;
            border: 1px solid rgba(0, 255, 163, 0.1);
        }

        .user {
            background: var(--user-msg-bg);
            color: white;
            border-bottom-right-radius: 4px;
            align-self: flex-end;
            margin-left: auto; /* Push to right */
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .chat-footer {
            padding: 20px;
            background: rgba(0,0,0,0.2);
            border-top: 1px solid var(--glass-border);
        }

        .input-group-custom {
            background: rgba(255,255,255,0.05);
            border-radius: 50px;
            padding: 5px;
            border: 1px solid var(--glass-border);
            display: flex;
            align-items: center;
            transition: 0.3s;
        }
        
        .input-group-custom:focus-within {
            border-color: var(--primary-color);
            box-shadow: var(--accent-glow);
            background: rgba(0,0,0,0.3);
        }

        .chat-input {
            background: transparent;
            border: none;
            color: white;
            padding: 12px 20px;
            flex: 1;
            outline: none;
            font-size: 1rem;
        }

        .btn-send {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: var(--primary-color);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: black;
            transition: 0.3s;
            cursor: pointer;
        }
        
        .btn-send:hover { transform: scale(1.1); box-shadow: 0 0 15px var(--primary-color); }

        .typing-indicator {
            font-size: 0.8rem;
            color: var(--primary-color);
            margin-bottom: 10px;
            margin-left: 10px;
            display: none;
            font-style: italic;
        }
        
    </style>
</head>
<body>

<video autoplay muted loop class="video-bg">
    <source src="https://cdn.pixabay.com/video/2020/05/25/40149-424930030_large.mp4" type="video/mp4">
</video>
<div class="video-overlay"></div>

<div class="container-fluid">
    <div class="chat-wrapper">
        <div class="chat-card">
            
            <!-- Header -->
            <div class="chat-header">
                <div class="mars-avatar">🪐</div>
                <div>
                    <h5 class="mb-0 fw-bold text-white">MARS AI</h5>
                    <small class="text-white-50">Performance Architect • Online</small>
                </div>
                <div class="ms-auto">
                    <a href="member_dashboard.php" class="btn btn-sm btn-outline-light rounded-pill px-3">Exit</a>
                </div>
            </div>

            <!-- Body -->
            <div class="chat-body" id="chatBox">
                <!-- Messages will appear here -->
            </div>

            <!-- Typing Indicator -->
            <div class="typing-indicator" id="typingIndicator">
                Mars is analyzing...
            </div>

            <!-- Footer -->
            <div class="chat-footer">
                <form id="chatForm" onsubmit="event.preventDefault(); sendMessage();">
                    <div class="input-group-custom">
                        <input type="text" id="userInput" class="chat-input" placeholder="Ask about workouts, diet, or injuries..." autocomplete="off">
                        <button type="submit" class="btn-send">➤</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
// --- MARS AI KNOWLEDGE BASE (EXPANDED) ---
// (Keeping KB same as before)
const KB = {
    "chest": { text: "🏋️ **MARS PROTOCOL: PECTORAL HYPERTROPHY**\n\n1. **Barbell Bench Press** (Compound) - 4 sets x 6 reps (Heavy)\n2. **Incline Dumpbell Press** (Upper Chest) - 3 sets x 10 reps\n3. **Weighted Dips** (Lower Chest) - 3 sets to failure\n4. **Cable Crossovers** (Isolation) - 4 sets x 15 reps\n\n*Coach Note: Retract your scapula to protect shoulders. Focus on the squeeze.*", image: "https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?q=80&w=1470&auto=format&fit=crop" },
    "back": { text: "🦍 **MARS PROTOCOL: V-TAPER DEVELOPMENT**\n\n1. **Deadlifts** (Posterior Chain) - 3 sets x 5 reps\n2. **Weighted Pull-Ups** (Width) - 4 sets x 8 reps\n3. **Chest-Supported Rows** (Thickness) - 4 sets x 12 reps\n4. **Face Pulls** (Rear Delts/Posture) - 4 sets x 20 reps", image: "https://images.unsplash.com/photo-1603287681836-e5452e8735e7?q=80&w=1470&auto=format&fit=crop" },
    "legs": { text: "🦵 **MARS PROTOCOL: LOWER BODY DESTRUCTION**\n\n1. **Barbell Back Squat** - 5 sets x 5 reps\n2. **Bulgarian Split Squats** - 3 sets x 10 reps (The Widowmaker)\n3. **Romanian Deadlifts** (Hamstrings) - 4 sets x 12 reps\n4. **Seated Calf Raises** - 5 sets x 15 reps", image: "https://images.unsplash.com/photo-1541534741688-6078c6bfb5c5?q=80&w=1469&auto=format&fit=crop" },
    "ppl": { text: "📅 **PUSH / PULL / LEGS (PPL) SPLIT**\n\nThis is the optimal frequency for natural lifters (6 Days/Week):\n\n- **Day 1: PUSH** (Chest, Shoulders, Triceps)\n- **Day 2: PULL** (Back, Biceps, Rear Delts)\n- **Day 3: LEGS** (Quads, Hams, Calves)\n- **Day 4: PUSH** (Hypertrophy Focus)\n- **Day 5: PULL** (Hypertrophy Focus)\n- **Day 6: LEGS** (Hypertrophy Focus)\n- **Day 7: REST**" },
    "keto": { text: "🥑 **KETOGENIC PROTOCOL**\n\nState: **Metabolic Ketosis** (Burning fat for fuel)\n\n✅ **FUEL:** Fatty Fish (Salmon), Avocados, MCT Oil, Eggs, Macadamia Nuts, Steak.\n❌ **TOXINS:** Sugar, Bread, Rice, Pasta, Fruits (High GI).\n\n*Warning: Expect 'Keto Flu' in first 3-5 days. Keep electrolytes high.*" },
    "fasting": { text: "⏳ **INTERMITTENT FASTING (16:8)**\n\n- **Fasting Window**: 16 Hours (Only Water/Black Coffee)\n- **Feeding Window**: 8 Hours (2-3 large meals)\n\n*Benefits: Increases HGH (Human Growth Hormone), improves insulin sensitivity, simplifies calorie deficit.*" },
    "creatine": { text: "🧪 **COMPOUND: CREATINE MONOHYDRATE**\n\n- **Status**: Safe & Effective.\n- **Mechanism**: Increases Phosphocreatine stores = More ATP = More reps.\n- **Dosage**: 5g Daily.\n- **Result**: Water retention is INTRAMUSCULAR (Good), makes muscles look fuller." },
    "shoulder pain": { text: "⚠️ **WARNING: SHOULDER IMPINGEMENT DETECTED**\n\nCommon Causes:\n1. Internal rotation during bench press.\n2. Weak rear delts.\n\n**Corrective Actions:**\n1. Stop heavy pressing immediately.\n2. Perform **Face Pulls** daily.\n3. Perform **Dead Hangs**.", image: "https://images.unsplash.com/photo-1541600383005-565c949cf777?q=80&w=1470&auto=format&fit=crop" },
    "sleep": { text: "💤 **SLEEP HYGIENE PROTOCOL**\n\nMuscle grows during SLEEP, not training.\n\n1. **Blackout Room**: Total darkness.\n2. **Temperature**: Cool room (18-20°C).\n3. **No Blue Light**: Screens off 1 hour before bed.", image: "https://images.unsplash.com/photo-1520206183501-b80df610434f?q=80&w=1471&auto=format&fit=crop" },
    "hello": { text: "👋 **MARS ONLINE.**\n\nI am your advanced performance architect.\n\nQuery me on:\n- **Advanced Splits** (PPL, Upper/Lower)\n- **Bio-Hacking** (Fasting, Cold Showers)\n- **Injury Rehab** (Shoulder, Knee)\n- **Nutrition Science**" }
};

const IMAGE_MAP = {
    "food": "https://images.unsplash.com/photo-1504674900247-0877df9cc836?q=80&w=1470&auto=format&fit=crop",
    "diet": "https://images.unsplash.com/photo-1490645935967-10de6ba17061?q=80&w=1453&auto=format&fit=crop",
    "run": "https://images.unsplash.com/photo-1476480862126-209bfaa8edc8?q=80&w=1470&auto=format&fit=crop",
    "gym": "https://images.unsplash.com/photo-1540497077202-7c8a3999166f?q=80&w=1470&auto=format&fit=crop",
    "yoga": "https://images.unsplash.com/photo-1544367563-12123d8965cd?q=80&w=1470&auto=format&fit=crop",
    "bicep": "https://images.unsplash.com/photo-1605296867304-46d5465a13f1?q=80&w=1470&auto=format&fit=crop"
};

window.onload = function() {
    let msgDiv = document.createElement("div");
    msgDiv.className = "message bot";
    msgDiv.id = "introMsg";
    document.getElementById("chatBox").appendChild(msgDiv);
    
    // Simple text for intro, no typewriter to keep it snappy or use a simpler one
    document.getElementById("introMsg").innerHTML = "<b>INITIATING...</b><br>Hello, I am MARS 🪐.<br>Your Personal Performance Architect.<br><br>How may I assist your evolution today?";
};

// --- NATURAL LANGUAGE PROCESSOR ---
function processText(text) {
    text = text.toLowerCase();
    // Fuzzy Corrections
    const corrections = {
        "cheast": "chest", "sholder": "shoulder", "biseps": "biceps", "legss": "legs"
    };
    for (let wrong in corrections) {
        if (text.includes(wrong)) text = text.replace(wrong, corrections[wrong]);
    }
    return text;
}

function getImageForContext(text) {
    for (let key in IMAGE_MAP) {
        if (text.toLowerCase().includes(key)) return IMAGE_MAP[key];
    }
    return null;
}

// Global variable for conversation state
window.conversationState = { offeringPlan: false };

async function sendMessage() {
    let input = document.getElementById("userInput");
    let rawMsg = input.value;
    if(!rawMsg) return;

    addMessage(rawMsg, 'user');
    input.value = "";
    document.getElementById("typingIndicator").style.display = "block";
    let chatBox = document.getElementById("chatBox");
    chatBox.scrollTop = chatBox.scrollHeight;
    
    let procMsg = processText(rawMsg);
    
    setTimeout(async () => {
        // --- MARS LOGIC ---
        let response = getLocalMarsResponse(procMsg);
        
        if (response) {
            document.getElementById("typingIndicator").style.display = "none";
            if(response.triggerPlan) window.conversationState.offeringPlan = true;
            if(response.confirmPlan) savePlan();
            addMessage(response.text, 'bot', response.image);
        } else {
            // Fallback
            try {
                // Determine if we need to call PHP backend or just give generic response
                // For now, simple fallback
                document.getElementById("typingIndicator").style.display = "none";
                addMessage("I am analyzing that request. For now, try asking about 'Chest', 'Legs', 'Keto', or 'Pain'.", 'bot');
            } catch (e) {
                document.getElementById("typingIndicator").style.display = "none";
                addMessage("⚠️ Neural Link Failed.", 'bot');
            }
        }
    }, 800);
}

function getLocalMarsResponse(msg) {
    if (msg.includes("yes") && window.conversationState.offeringPlan) {
        window.conversationState.offeringPlan = false;
        return { confirmPlan: true, text: "✅ **PLAN GENERATED & SAVED.**\n\nAccess your weekly protocol in **Profile > My Plans**." };
    }
    for (let key in KB) {
        if (msg.includes(key)) return KB[key];
    }
    if (msg.includes("diet") || msg.includes("plan")) {
        return { 
            triggerPlan: true, 
            text: "📋 **PROTOCOL UPGRADE AVAILABLE**\n\nShall I generate a full **Weekly Optimization Plan** for you?\n\nType **'Yes'** to confirm." 
        };
    }
    return null; 
}

function addMessage(text, sender, imageUrl = null) {
    let box = document.getElementById("chatBox");
    let div = document.createElement("div");
    div.classList.add("message", sender);
    
    // Markdown-ish parsing
    let content = text
        .replace(/\n/g, '<br>')
        .replace(/\*\*(.*?)\*\*/g, '<b>$1</b>')
        .replace(/\*(.*?)\*/g, '<i>$1</i>');
        
    if (imageUrl) {
         content += `<br><img src="${imageUrl}" style="max-width:100%; border-radius:10px; margin-top:10px;">`;
    }
    div.innerHTML = content;
    box.appendChild(div);
    box.scrollTop = box.scrollHeight;
}

function savePlan() {
    const plan = `## 🪐 MARS PROTOCOL: WEEKLY OPTIMIZATION
**Objective:** Peak Human Performance

### MONDAY: PUSH (Strength)
*   Bench Press: 5x5
*   OHP: 4x8
*   **Fuel:** High Carb (Rice/Oats)

### TUESDAY: PULL (Hypertrophy)
*   Deadlift: 3x5
*   Pullups: 4xFailure
*   **Fuel:** High Protein (Steak/Eggs)

### WEDNESDAY: ACTIVE RECOVERY
*   Mobility Drills
*   Light Cardio (Zone 2)
*   **Fuel:** Fasting Window 16:8

### THURSDAY: LEGS (Power)
*   Squats: 5x5
*   Lunges: 3x12
*   **Fuel:** High Carb Re-feed

### FRIDAY: UPPER BODY (Pump)
*   Arms & Shoulders Isolation
*   **Fuel:** Moderate Carb

### WEEKEND: METABOLIC RESET
*   Nature Hike / Sprint Intervals
*   **Fuel:** Cheat Meal (Saturday PM Only)`;

    fetch('backend/save_plan.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ plan: plan })
    }).then(r => r.json()).then(d => console.log("Plan Saved"));
}
</script>
</body>
</html>

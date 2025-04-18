document.addEventListener('DOMContentLoaded', () => {
    const num1Input = document.getElementById('num1');
    // const num2Input = document.getElementById('num2'); // We get it but don't use it for API
    const generateArmiesButton = document.getElementById('generateArmiesButton');
    const army1Container = document.getElementById('army1-container');
    const army2Container = document.getElementById('army2-container');
    const army1UnitsDiv = document.getElementById('army1-units');
    const army2UnitsDiv = document.getElementById('army2-units');
    const fightButton = document.getElementById('fightButton');
    const fightButtonContainer = document.getElementById('fight-button-container');
    const resultMessageDiv = document.getElementById('result-message');
    const tooltip = document.getElementById('unit-tooltip'); // Get the tooltip element
    let army1Data = null;
    let army2Data = null;
    const MAX_UNITS_PER_LINE = 30; // As per requirement

    // --- Helper Functions ---

    function getUnitColorClass(troopType) {
        switch (troopType?.toUpperCase()) { // Use optional chaining and uppercase for safety
            case 'MELEE':
                return 'melee';
            case 'CAVALRY':
                return 'cavalry';
            case 'RANGED': // Assuming "Archer" unitType corresponds to "RANGED" troopType
                return 'archer';
            default:
                return ''; // Default or unknown type
        }
    }

    async function fetchArmyData(size) {
        // Basic validation
        if (isNaN(size) || size <= 0) {
            throw new Error("Invalid army size requested.");
        }
        try {
            // ---- IMPORTANT ----
            // Replace '/api.php?armySize=' with your ACTUAL API endpoint if it differs.
            // If you don't have a running backend, uncomment the mock function below.
            const response = await fetch(`/api.php?armySize=${size}`);

            // *** MOCK FUNCTION (Uncomment below and comment out fetch above if no backend) ***
            // const response = await mockFetchArmyData(size);
            // *** END MOCK FUNCTION ***

            if (!response.ok) {
                throw new Error(`API Error: ${response.status} ${response.statusText}`);
            }
            const data = await response.json();
            // Basic validation of expected structure
            if (!data || !data.troops || !Array.isArray(data.troops)) {
                throw new Error("Invalid data structure received from API.");
            }
            return data;
        } catch (error) {
            console.error("Failed to fetch army data:", error);
            throw error; // Re-throw to be caught by the caller
        }
    }

    function drawArmy(armyData, containerDiv) {
        containerDiv.innerHTML = ''; // Clear previous drawing
        if (!armyData || !armyData.troops) return;

        let currentLineCount = 0;

        armyData.troops.forEach(troopGroup => {
            // Ensure troopGroup and its units array are valid
            if (!troopGroup || !troopGroup.units || !Array.isArray(troopGroup.units) || troopGroup.units.length === 0) {
                console.warn("Skipping troop group with no valid units array:", troopGroup);
                return;
            }

            const unitName = troopGroup.unitType || 'Unknown Unit';

            troopGroup.units.forEach(individualUnit => {
                // Make sure the individual unit data is valid
                if (!individualUnit || !individualUnit.troopType) {
                    console.warn(`Skipping invalid individual unit data within group '${unitName}':`, individualUnit);
                    return; // Skip this specific square if unit data is bad
                }

                // Check for line wrapping
                if (currentLineCount >= MAX_UNITS_PER_LINE) {
                    // We rely on flex-wrap, just reset the counter conceptually
                    currentLineCount = 0;
                }

                // Get properties for THIS specific unit
                const troopType = individualUnit.troopType;
                const colorClass = getUnitColorClass(troopType);
                const health = individualUnit.health ?? 'N/A'; // Use default if undefined/null
                const attack = individualUnit.attack ?? 'N/A';
                const defense = individualUnit.defense ?? 'N/A';

                // Create the square element
                const unitSquare = document.createElement('div');
                unitSquare.classList.add('unit-square', colorClass);

                unitSquare.dataset.unitName = unitName; // Name is from the group
                unitSquare.dataset.health = health;
                unitSquare.dataset.attack = attack;
                unitSquare.dataset.defense = defense;
                // ----------------------------------------------------

                containerDiv.appendChild(unitSquare);
                currentLineCount++;
            }); // End of loop for individual units

            // Optional: Add a warning if count mismatches units.length, indicating potential API data issue
            if (troopGroup.count !== troopGroup.units.length) {
                console.warn(`Potential data issue for ${unitName}: API 'count' (${troopGroup.count}) differs from actual 'units' array length (${troopGroup.units.length}). Displayed based on units array.`);
            }

        }); // End of loop for troop groups
    }

    function showTooltip(event) {
        const target = event.target;
        // Check if the hovered element is a unit square
        if (target.classList.contains('unit-square')) {
            const name = target.dataset.unitName;
            const hp = target.dataset.health;
            const atk = target.dataset.attack;
            const def = target.dataset.defense;

            tooltip.innerHTML = `
                <strong>${name}</strong><br>
                HP: ${hp}<br>
                ATK: ${atk}<br>
                DEF: ${def}
            `;

            // Position the tooltip near the cursor
            // Add small offsets (e.g., 10px) to prevent immediate mouseout
            const scrollX = window.scrollX || window.pageXOffset;
            const scrollY = window.scrollY || window.pageYOffset;
            tooltip.style.left = `${event.pageX + 10 - scrollX}px`;
            tooltip.style.top = `${event.pageY + 10 - scrollY}px`;

            tooltip.style.display = 'block'; // Show the tooltip
            tooltip.style.opacity = 1; // Make it visible if using transition
        }
    }

    function hideTooltip() {
        tooltip.style.display = 'none'; // Hide the tooltip
        tooltip.style.opacity = 0; // Make it invisible if using transition
    }

    function updateTooltipPosition(event) {
        // Update position only if the tooltip is visible
        if (tooltip.style.display === 'block') {
            const scrollX = window.scrollX || window.pageXOffset;
            const scrollY = window.scrollY || window.pageYOffset;
            tooltip.style.left = `${event.pageX + 10 - scrollX}px`;
            tooltip.style.top = `${event.pageY + 10 - scrollY}px`;
        }
    }
    /**
     * Calculates the total power of a single army based on the sum of
     * health, attack, and defense of all its individual units.
     * @param {object} armyData - The army data object from the API.
     * @returns {number} The calculated total power of the army.
     */
    function calculateArmyPower(armyData) {
        let totalPower = 0;

        if (!armyData || !armyData.troops || !Array.isArray(armyData.troops)) {
            console.warn("Cannot calculate power: Invalid army data structure provided.", armyData);
            return 0; // Return 0 if the basic structure is invalid
        }

        armyData.troops.forEach(troopGroup => {
            // Ensure the units array exists and is an array
            if (troopGroup.units && Array.isArray(troopGroup.units)) {
                // Iterate over EACH unit object in the units array
                troopGroup.units.forEach(individualUnit => {
                    // Add stats only if they are valid numbers, default to 0 otherwise
                    const health = individualUnit?.health ?? 0; // Optional chaining + nullish coalescing
                    const attack = individualUnit?.attack ?? 0;
                    const defense = individualUnit?.defense ?? 0;

                    // Add this unit's contribution to the total power
                    totalPower += health + attack + defense;

                    // Optional: Add stricter type checking if needed
                    /*
                    if (typeof health === 'number' && typeof attack === 'number' && typeof defense === 'number') {
                        totalPower += health + attack + defense;
                    } else {
                        console.warn("Skipping unit in power calculation due to non-numeric stats:", individualUnit, "in group:", troopGroup.unitType);
                    }
                    */
                });
            } else {
                console.warn("Skipping troop group in power calculation due to missing/invalid units array:", troopGroup);
            }
        });

        return totalPower;
    }
    function calculateWinner(army1, army2) {
        if (!army1 || !army2) {
            return "Cannot determine winner: One or both armies are missing.";
        }

        const power1 = calculateArmyPower(army1);
        const power2 = calculateArmyPower(army2);

        console.log(`Army 1 Power (Individual Sum): ${power1}, Army 2 Power (Individual Sum): ${power2}`); // For debugging

        if (power1 > power2) {
            return power1+" > "+power2+"; Army 1 Wins!";
        } else if (power2 > power1) {
            return power1+" < "+power2+"; Army 2 Wins!";
        } else if (power1 === 0 && power2 === 0 && (!army1.troops || army1.troops.length === 0) && (!army2.troops || army2.troops.length === 0)) {
            // Refined condition: only show this if armies were truly empty/invalid from the start
            return "Cannot determine winner (both armies appear empty or invalid).";
        } else if (power1 === power2) { // Catch all other equal power scenarios
            return "It's a Draw!";
        } else {
            // Should not happen with the logic above, but as a fallback:
            return "Could not determine the winner.";
        }
    }

    function resetBattlefield() {
        army1UnitsDiv.innerHTML = '';
        army2UnitsDiv.innerHTML = '';
        resultMessageDiv.textContent = '';
        fightButton.style.display = 'none';
        fightButton.disabled = false; // Re-enable if disabled
        army1Container.classList.remove('moving-left');
        army2Container.classList.remove('moving-right');
        // Reset transforms if necessary (though 'forwards' fill mode keeps them)
        army1Container.style.transform = 'translateX(0)';
        army2Container.style.transform = 'translateX(0)';
        army1Data = null;
        army2Data = null;
    }

    // --- Event Listeners ---

    generateArmiesButton.addEventListener('click', async () => {
        const armySize = parseInt(num1Input.value, 10);

        if (isNaN(armySize) || armySize <= 0) {
            alert("Please enter a valid positive number for the army size.");
            return;
        }

        resetBattlefield(); // Clear previous state
        resultMessageDiv.textContent = "Generating armies...";

        try {
            // Perform API calls in parallel
            [army1Data, army2Data] = await Promise.all([
                fetchArmyData(armySize),
                fetchArmyData(armySize) // Uses the *first* number for both calls as requested
            ]);

            // Draw armies
            drawArmy(army1Data, army1UnitsDiv);
            drawArmy(army2Data, army2UnitsDiv);

            // Show fight button and clear loading message
            fightButton.style.display = 'block';
            resultMessageDiv.textContent = ''; // Clear "Generating..."

        } catch (error) {
            resultMessageDiv.textContent = `Error: ${error.message}`;
            console.error("Army generation failed:", error);
            // Ensure button is hidden if generation fails
            fightButton.style.display = 'none';
        }
    });

    fightButton.addEventListener('click', () => {
        if (!army1Data || !army2Data) {
            console.error("Cannot fight, army data is missing.");
            return;
        }

        fightButton.disabled = true; // Prevent multiple clicks
        resultMessageDiv.textContent = 'Fighting...';

        // Add animation classes
        army1Container.classList.add('moving-left');
        army2Container.classList.add('moving-right');

        // Wait for animation to complete (3 seconds)
        setTimeout(() => {
            // Calculate and display winner *after* animation
            const winnerMessage = calculateWinner(army1Data, army2Data);
            resultMessageDiv.textContent = winnerMessage;

            // Optional: Keep button disabled or hide it completely after fight
            // fightButton.style.display = 'none';

        }, 3000); // 3000ms = 3 seconds
    });

    army1UnitsDiv.addEventListener('mouseover', showTooltip);
    army1UnitsDiv.addEventListener('mouseout', hideTooltip);
    army1UnitsDiv.addEventListener('mousemove', updateTooltipPosition); // Keep tooltip near cursor

    army2UnitsDiv.addEventListener('mouseover', showTooltip);
    army2UnitsDiv.addEventListener('mouseout', hideTooltip);
    army2UnitsDiv.addEventListener('mousemove', updateTooltipPosition); // Keep tooltip near cursor

}); // End DOMContentLoaded
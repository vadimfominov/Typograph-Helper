window.addEventListener('load', function() {
	// Используем элементы из настроек, если они есть
	const elementSelectors = typographySettings.elements || ['h2', 'h4', 'p', 'span'];
	
	// Создаем селектор для querySelectorAll
	const selector = elementSelectors.join(', ');
	const elements = document.querySelectorAll(selector);

	// Используем предлоги из настроек, если они есть
	const prepositions = typographySettings.prepositions || [
		 'и', 'а', 'в', 'на', 'с', 'к', 'по', 'во', 'о', 'об', 
		 'у', 'от', 'до', 'из', 'за', 'для', 'под', 'про', 
		 'над', 'без', 'через', 'при', 'перед'
	];

	if(elements) {
		elements.forEach(element => {
			let text = element.innerHTML.replace(/\s+/g, ' ');
			if(text.length > 0) {
				const hasPreposition = prepositions.some(prep => 
					text.toLowerCase().includes(` ${prep} `)
				);
				if (hasPreposition) {
					prepositions.forEach(prep => {
						const regex = new RegExp(`(\\s${prep})\\s`, 'gi');
						text = text.replace(regex, `$1\u00A0`);
					});
					element.innerHTML = text;
				}
			}
		});
	}
});

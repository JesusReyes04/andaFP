const suggestions = [
    "Actividades Ecuestres",
    "Guía en el Medio Natural y de Tiempo Libre",
    "Gestión Administrativa",
    "Aprovechamiento y Conservación del Medio Natural",
    "Jardinería y Floristería",
    "Producción Agroecológica",
    "Producción Agropecuaria",
    "Impresión Gráfica",
    "Postimpresión y Acabados Gráficos",
    "Preimpresión Digital",
    "Actividades Comerciales",
    "Comercialización de Productos Alimentarios",
    "Construcción",
    "Obras de Interior, Decoración y Rehabilitación",
    "Instalaciones Eléctricas y Automáticas",
    "Instalaciones de Telecomunicaciones",
    "Redes y Estaciones de Tratamiento de Aguas",
    "Conformado por Moldeo de Metales y Polímeros",
    "Mecanizado",
    "Montaje de Estructuras e Instalación de Sistemas Aeronáuticos",
    "Soldadura y Calderería",
    "Joyería",
    "Cocina y Gastronomía",
    "Comercialización de Productos Alimentarios",
    "Servicios en Restauración",
    "Estética y Belleza",
    "Peluquería y Cosmética Capilar",
    "Vídeo Disc-Jockey y Sonido",
    "Aceites de Oliva y Vinos",
    "Elaboración de Productos Alimenticios",
    "Panadería, Repostería y Confitería",
    "Excavaciones y Sondeos",
    "Piedra Natural",
    "Sistemas Microinformáticos y Redes",
    "Instalaciones Frigoríficas y de Climatización",
    "Instalaciones de Producción de Calor",
    "Mantenimiento Electromecánico",
    "Carpintería y Mueble",
    "Instalación y Amueblamiento",
    "Procesado y Transformación de la Madera",
    "Cultivos Acuícolas",
    "Mantenimiento y Control de la Maquinaria de Buques y Embarcaciones",
    "Navegación y Pesca de Litoral",
    "Operaciones Subacuáticas e Hiperbáricas",
    "Operaciones de Laboratorio",
    "Planta Química",
    "Emergencias Sanitarias",
    "Farmacia y Parafarmacia",
    "Cuidados Auxiliares de Enfermería",
    "Emergencias y Protección Civil",
    "Sanidad Ambiental Aplicada",
    "Seguridad",
    "Atención a Personas en Situación de Dependencia",
    "Calzado y Complementos de Moda",
    "Confección y Moda",
    "Fabricación y Ennoblecimiento de Productos Textiles",
    "Carrocería",
    "Conducción de Vehículos de Transporte por Carretera",
    "Electromecánica de Maquinaria",
    "Electromecánica de Vehículos Automóviles",
    "Mantenimiento de Embarcaciones de Recreo",
    "Mantenimiento de Estructuras de Madera y Mobiliario de Embarcaciones de Recreo",
    "Mantenimiento de Material Rodante Ferroviario",
    "Montaje de Estructuras e Instalación de Sistemas Aeronáuticos",
    "Fabricación de Productos Cerámicos",

    // superior
    "Acondicionamiento Físico",
    "Enseñanza y Animación Sociodeportiva",
    "Administración y Finanzas",
    "Asistencia a la Dirección",
    "Ganadería y Asistencia en Sanidad Animal",
    "Gestión Forestal y del Medio Natural",
    "Paisajismo y Medio Rural",
    "Gestión Forestal y del Medio Natural",
    "Ganadería y Asistencia en Sanidad Animal",
    "Diseño y Gestión de la Producción Gráfica",
    "Transporte y Logística",
    "Marketing y Publicidad",
    "Gestión de Ventas y Espacios Comerciales",
    "Comercio Internacional",
    "Proyectos de Edificación",
    "Proyectos de Obra Civil",
    "Organización y Control de Obras de Construcción",
    "Sistemas Electrotécnicos y Automatizados",
    "Sistemas de Telecomunicaciones e Informáticos",
    "Mantenimiento Electrónico",
    "Automatización y Robótica Industrial",
    "Electromedicina Clínica",
    "Eficiencia Energética y Energía Solar Térmica",
    "Energías Renovables",
    "Gestión del Agua",
    "Construcciones Metálicas",
    "Programación de la Producción en Moldeo de Metales y Polímeros",
    "Diseño en Fabricación Mecánica",
    "Programación de la Producción en Fabricación Mecánica",
    "Óptica de anteojería",
    "Gestión de Alojamientos Turísticos",
    "Agencias de Viajes y Gestión de Eventos",
    "Guía, Información y Asistencias Turísticas",
    "Dirección de Cocina",
    "Dirección de Servicios en Restauración",
    "Estética Integral y Bienestar",
    "Estilismo y Dirección de Peluquería",
    "Caracterización y Maquillaje Profesional",
    "Asesoría de Imagen Personal y Corporativa",
    "Termalismo y Bienestar",
    "Realización de Proyectos Audiovisuales y Espectáculos",
    "Sonido para Audiovisuales y Espectáculos",
    "Iluminación, Captación y Tratamiento de Imagen",
    "Producción de Audiovisuales y Espectáculos",
    "Animaciones 3D, Juegos y Entornos Interactivos",
    "Vitivinicultura",
    "Procesos y Calidad en la Industria Alimentaria",
    "Administracion de Sistemas Informáticos en Red",
    "Desarrollo de Aplicaciones Web",
    "Desarrollo de Aplicaciones Multiplataforma",
    "Desarrollo de Proyectos de Instalaciones Térmicas y de Fluidos",
    "Mecatrónica Industrial",
    "Mantenimiento de Instalaciones Térmicas y de Fluidos",
    "Prevención de riesgos profesionales",
    "Diseño y Amueblamiento",
    "Transporte Marítimo y Pesca de Altura",
    "Acuicultura",
    "Organización del Mantenimiento de Maquinaria de Buques y Embarcaciones",
    "Laboratorio de Análisis y de Control de Calidad",
    "Química Industrial",
    "Fabricación de productos farmacéuticos, biotecnológicos y afines",
    "Audiología Protésica",
    "Radioterapia y Dosimetría",
    "Laboratorio Clínico y Biomédico",
    "Imagen para el Diagnóstico y Medicina Nuclear",
    "Higiene Bucodental",
    "Documentación y Administración Sanitarias",
    "Anatomía Patológica y Citodiagnóstico",
    "Prótesis Dentales",
    "Ortoprótesis y Productos de Apoyo",
    "Dietética",
    "Educación y Control Ambiental",
    "Coordinación de Emergencias y Protección Civil",
    "Química y Salud Ambiental",
    "Educación Infantil",
    "Animación Sociocultural y Turística",
    "Promoción de Igualdad de Género",
    "Integración Social",
    "Mediación Comunicativa",
    "Formación para la Movilidad Segura y Sostenible",
    "Vestuario a Medida y de Espectáculos",
    "Patronaje y Moda",
    "Automoción",
    "Mantenimiento aeromecánico de aviones con motor de turbina",
    "Mantenimiento aeromecánico de helicópteros con motor de turbina"
];

const placeSuggestions = [
    "Huelava",
    "Sevilla",
    "Cádiz",
    "Córdoba",
    "Granada",
    "Jaén",
    "Málaga",
    "Almería"
];

// Función para mostrar sugerencias del primer input
function showSuggestions() {
    const input = document.getElementById('searchInput').value;
    const suggestionsList = document.getElementById('suggestionsList');

    const filteredSuggestions = suggestions.filter(suggestion =>
        suggestion.toLowerCase().includes(input.toLowerCase())
    );

    if (filteredSuggestions.length > 0 && input !== "") {
        suggestionsList.innerHTML = filteredSuggestions.map(suggestion =>
            `<li onclick="selectSuggestion('${suggestion}', 'searchInput', 'suggestionsList')">${suggestion}</li>`
        ).join('');
        suggestionsList.style.display = 'block';
    } else {
        suggestionsList.innerHTML = '';
        suggestionsList.style.display = 'none';
    }
}

// Función para mostrar sugerencias del segundo input
function showPlaceSuggestions() {
    const input = document.getElementById('placeInput').value;
    const suggestionsList = document.getElementById('placeSuggestionsList');

    const filteredSuggestions = placeSuggestions.filter(suggestion =>
        suggestion.toLowerCase().includes(input.toLowerCase())
    );

    if (filteredSuggestions.length > 0 && input !== "") {
        suggestionsList.innerHTML = filteredSuggestions.map(suggestion =>
            `<li onclick="selectSuggestion('${suggestion}', 'placeInput', 'placeSuggestionsList')">${suggestion}</li>`
        ).join('');
        suggestionsList.style.display = 'block';
    } else {
        suggestionsList.innerHTML = '';
        suggestionsList.style.display = 'none';
    }
}

// Función genérica para seleccionar sugerencia en cualquier input
function selectSuggestion(suggestion, inputId, listId) {
    const input = document.getElementById(inputId);
    input.value = suggestion;
    document.getElementById(listId).style.display = 'none';
}

function validateSearch(placeInput, searchInput) {
    const place = placeInput.value.trim().toLowerCase();
    const grado = searchInput.value.trim().toLowerCase();

    const validPlace = placeSuggestions.some(p => p.toLowerCase() === place);
    const validGrado = suggestions.some(g => g.toLowerCase() === grado);

    if (!validPlace || !validGrado) {
        showError("El contenido de los campos debe coincidir con alguna de las sugerencias");
        return;
    }

    // Aquí haces la búsqueda real
    console.log("Buscar con:", { lugar: place, grado: grado });
}

function showError(mensaje) {
    const errorDiv = document.createElement("div");
    errorDiv.innerText = mensaje;
    errorDiv.classList.add("error-message");

    document.body.appendChild(errorDiv);

    // Mostrar el mensaje de error
    errorDiv.style.display = "block";

    setTimeout(() => {
        errorDiv.remove();
    }, 3000);
}

function validateInputsValues(e, placeInput, searchInput) { 
    placeInput = placeInput.value.trim().toLowerCase();
    searchInput = searchInput.value.trim().toLowerCase();
    const validPlace = placeSuggestions.some(p => p.toLowerCase() === placeInput);
    const validGrado = suggestions.some(g => g.toLowerCase() === searchInput);

    if (!validPlace || !validGrado) {
        showError("El contenido de los campos debe coincidir con alguna de las sugerencias.");
        e.preventDefault();
        return;
    }
}
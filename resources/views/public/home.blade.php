@extends('layouts.seven.public_app')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --nasa-blue: #0B3D91;
            --nasa-red: #FC3D21;
            --nasa-white: #FFFFFF;
            --nasa-dark: #0A0A1A;
            --sky-gradient: linear-gradient(135deg, #1a73e8, #34a853);
            --jamaica-green: #00A651;
            --jamaica-yellow: #FED100;
            --jamaica-black: #000000;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', 'NASA Sans', sans-serif;
        }

        body {
            background-color: var(--nasa-dark);
            color: var(--nasa-white);
            overflow-x: hidden;
        }

        #vanta-globe {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        @media (max-width: 768px) {
            #vanta-globe {
                transform: translateX(-10%); /* Counter the desktop-projection offset */
                width: 120%; /* Ensure no edges are visible during translation */
                left: -10%;
            }
        }



        /* Body Padding for Fixed Navbar */
        body {
            padding-top: 76px;
        }

        /* Hero Section (NASA-Style) with Christmas Theme */
        .hero {
            height: 100vh;
            display: flex;
            align-items: flex-start;
            padding: calc(5% + 76px) 5% 0 5%;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(220, 20, 60, 0.1), rgba(34, 139, 34, 0.1));
            z-index: 1;
            pointer-events: none;
        }

        .hero-content {
            max-width: 600px;
            z-index: 10;
            margin-top: -120px;
            /* Move text upwards */
            position: relative;
        }

        /* Hero Slider Container - REPLACED WITH WINDOW STYLE */
        #hero-slider-container {
            /* Styles moved to new window component block below */
        }


        /* Carousel Styling to match previous image style */
        .hero-carousel {
            width: 100%;
            height: 100%;
            border-radius: 15px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
            border: 2px solid rgba(255, 255, 255, 0.1);
            overflow: hidden;
            /* background: rgba(0,0,0,0.2);*/
            /* Optional: slight dark background behind slides */
        }

        .hero-carousel .carousel-item {
            height: 100%;
        }

        .hero-carousel img {
            width: 100%;
            height: 400px;
            object-fit: cover;
        }

        /* Responsive adjustments for slider - REPLACED WITH WINDOW STYLE */
        @media (max-width: 768px) {
            /* Styles moved to new window component block below */
        }

        .hero-carousel img {
            height: 300px;
        }

        /* Hero Text Styles - CYBER UPDATE */
        .hero-content h1 {
            font-size: 4rem;
            font-weight: 800;
            color: #FFFFFF;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 1.5rem;
            text-shadow:
                0 0 10px rgba(0, 166, 81, 0.5),
                0 0 20px rgba(0, 166, 81, 0.3),
                0 0 40px rgba(0, 166, 81, 0.1);
            background: linear-gradient(to right, #ffffff, #e0e0e0);
            -webkit-background-clip: text;
        }

        .subtitle {
            font-size: 1.25rem;
            margin-bottom: 2.5rem;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.6;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
            background: rgba(0, 20, 10, 0.4);
            /* Subtle backing for readability */
            padding: 1rem;
            border-radius: 10px;
            border-left: 3px solid var(--jamaica-yellow);
            backdrop-filter: blur(5px);
            max-width: 90%;
        }

        /* Cyber Buttons */
        .hero-buttons {
            display: flex;
            gap: 1.5rem;
        }

        .btn-cyber {
            position: relative;
            padding: 1rem 2.5rem;
            font-size: 1.1rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #fff;
            background: transparent;
            border: 2px solid;
            border-radius: 5px;
            /* Techy sharp corners or slightly rounded */
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            overflow: hidden;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-cyber-primary {
            border-color: var(--jamaica-green);
            background: linear-gradient(45deg, rgba(0, 166, 81, 0.2), rgba(0, 166, 81, 0.0));
            box-shadow: 0 0 15px rgba(0, 166, 81, 0.3);
        }

        .btn-cyber-primary:hover {
            background: var(--jamaica-green);
            box-shadow: 0 0 30px rgba(0, 166, 81, 0.6);
            transform: translateY(-2px);
            color: #fff;
        }

        .btn-cyber-secondary {
            border-color: var(--jamaica-yellow);
            color: var(--jamaica-yellow);
            background: linear-gradient(45deg, rgba(254, 209, 0, 0.1), rgba(254, 209, 0, 0.0));
        }

        .btn-cyber-secondary:hover {
            background: var(--jamaica-yellow);
            color: #000;
            box-shadow: 0 0 30px rgba(254, 209, 0, 0.5);
            transform: translateY(-2px);
        }




        /* Flight Tracker (3D Globe) */
        #globe-container {
            width: 100%;
            height: 600px;
            margin: 5rem 0;
            border-radius: 15px;
            overflow: hidden;
            border: 1px solid rgba(11, 61, 145, 0.5);
        }

        /* --- HOLOGRAPHIC PRISM COMPONENT START --- */
        #hero-slider-container {
            position: absolute;
            right: 5%;
            top: 50%;
            transform: translateY(-50%);
            width: 550px;
            height: 400px;
            z-index: 5;
            perspective: 1000px;
        }

        .holo-card {
            width: 100%;
            height: 100%;
            background: rgba(16, 30, 50, 0.6);
            border-radius: 20px;
            border: 1px solid rgba(0, 166, 81, 0.3);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-shadow:
                0 0 20px rgba(0, 166, 81, 0.1),
                inset 0 0 20px rgba(0, 166, 81, 0.05);
            position: relative;
            transform-style: preserve-3d;
            transform: perspective(1000px);
            overflow: hidden;
        }

        /* Tech/Scan Lines Overlay */
        .holo-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background:
                linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 255, 136, 0.03) 50%),
                linear-gradient(90deg, rgba(255, 0, 0, 0.03), rgba(0, 255, 0, 0.01), rgba(0, 0, 255, 0.03));
            background-size: 100% 4px, 100% 100%;
            z-index: 2;
            pointer-events: none;
            border-radius: 20px;
        }

        .holo-border-corner {
            position: absolute;
            width: 30px;
            height: 30px;
            border: 2px solid var(--jamaica-yellow);
            transition: all 0.3s ease;
            z-index: 3;
        }

        .tl {
            top: 20px;
            left: 20px;
            border-bottom: none;
            border-right: none;
        }

        .tr {
            top: 20px;
            right: 20px;
            border-bottom: none;
            border-left: none;
        }

        .bl {
            bottom: 20px;
            left: 20px;
            border-top: none;
            border-right: none;
        }

        .br {
            bottom: 20px;
            right: 20px;
            border-top: none;
            border-left: none;
        }

        .holo-content {
            position: relative;
            z-index: 4;
            height: 100%;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            transform: translateZ(20px);
        }

        .holo-image-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            overflow: hidden;
            border-radius: 20px;
        }

        .holo-slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1s ease;
        }

        .holo-slide.active {
            opacity: 1;
        }

        .holo-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* mask-image: linear-gradient(to bottom, black 60%, transparent 100%); */
        }

        /* HUD Data Interface */
        .holo-hud {
            background: rgba(0, 20, 10, 0.85);
            padding: 1.5rem;
            border-radius: 12px;
            border: 1px solid var(--jamaica-green);
            box-shadow: 0 0 15px rgba(0, 166, 81, 0.2);
            color: var(--jamaica-green);
            font-family: 'Courier New', monospace;
            transform: translateZ(30px);
            margin-bottom: 2rem;
            max-width: 80%;
            position: relative;
        }

        .holo-hud::before {
            content: "STATUS: ACTIVE";
            position: absolute;
            top: -12px;
            right: 20px;
            background: var(--jamaica-green);
            color: black;
            font-size: 0.7rem;
            padding: 2px 8px;
            font-weight: bold;
            border-radius: 4px;
        }

        .hud-title {
            font-size: 1.4rem;
            color: white;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 0.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding-bottom: 0.5rem;
            text-shadow: 0 0 5px rgba(255, 255, 255, 0.5);
        }

        .hud-specs {
            display: flex;
            gap: 1.5rem;
            font-size: 0.9rem;
        }

        .spec-box {
            display: flex;
            flex-direction: column;
        }

        .spec-label {
            font-size: 0.7rem;
            opacity: 0.7;
            margin-bottom: 2px;
        }

        .spec-value {
            color: var(--jamaica-yellow);
            font-weight: bold;
        }

        .scan-line {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 10px;
            background: rgba(0, 166, 81, 0.5);
            box-shadow: 0 0 10px var(--jamaica-green);
            opacity: 0.6;
            animation: scan 3s linear infinite;
            z-index: 5;
            pointer-events: none;
        }

        @keyframes scan {
            0% {
                top: 0%;
                opacity: 0;
            }

            10% {
                opacity: 0.8;
            }

            90% {
                opacity: 0.8;
            }

            100% {
                top: 100%;
                opacity: 0;
            }
        }

        @media (max-width: 992px) {
            #hero-slider-container {
                width: 450px;
                height: 350px;
            }
        }

        @media (max-width: 768px) {
            .hero {
                height: auto !important;
                min-height: 100vh;
                padding-bottom: 4rem;
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            #hero-slider-container {
                position: relative;
                width: 100%;
                max-width: 550px;
                height: auto;
                aspect-ratio: 16 / 10;
                min-height: 280px;
                right: auto;
                top: auto;
                transform: none;
                margin-top: 3rem;
                margin-left: auto;
                margin-right: auto;
            }

            .holo-hud {
                max-width: 95%;
                margin: 0 auto 1rem auto;
            }
        }

        /* --- HOLOGRAPHIC PRISM COMPONENT END --- */



        /* Fleet Showcase (NASA Data Cards) */
        .fleet-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin: 3rem 0;
        }

        .aircraft-card {
            background: rgba(30, 30, 45, 0.6);
            border-radius: 15px;
            padding: 2rem;
            transition: all 0.3s ease;
            border: 1px solid rgba(11, 61, 145, 0.2);
            backdrop-filter: blur(10px);
        }

        .aircraft-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(11, 61, 145, 0.2);
        }

        /* Stats Section */
        .stats-section {
            padding: 6rem 5%;
            background: #0a0a1a;
            position: relative;
        }

        .stats-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .stats-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .stats-title {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #ffffff;
            letter-spacing: 1px;
        }

        .stats-subtitle {
            font-size: 1.2rem;
            color: #a0a0a0;
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            margin-bottom: 4rem;
        }

        .stat-card {
            background: #1a1f2e;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid #2a2f3e;
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            border-color: #00a651;
        }

        .stat-icon {
            font-size: 2.5rem;
            color: #00a651;
            margin-bottom: 1rem;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 0.5rem;
            font-family: 'Arial', sans-serif;
        }

        .stat-label {
            font-size: 1.1rem;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .stat-description {
            font-size: 0.9rem;
            color: #b0b0b0;
            line-height: 1.5;
        }

        .achievements-showcase {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }

        .achievement-item {
            background: #1a1f2e;
            border-radius: 12px;
            padding: 2rem;
            display: flex;
            align-items: flex-start;
            gap: 1.5rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid #2a2f3e;
        }

        .achievement-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
            border-color: #fed100;
        }

        .achievement-icon {
            font-size: 2rem;
            color: #fed100;
            flex-shrink: 0;
        }

        .achievement-content h3 {
            font-size: 1.2rem;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 0.5rem;
            line-height: 1.4;
        }

        .achievement-content p {
            font-size: 0.9rem;
            color: #b0b0b0;
            line-height: 1.5;
        }

        @media (max-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .achievements-showcase {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .stats-section {
                padding: 4rem 1rem;
            }

            .stats-title {
                font-size: 2.5rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .stat-card {
                padding: 1.5rem;
            }

            .achievement-item {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }
        }

        /* Section Titles */
        .section-title {
            text-align: center;
            margin-bottom: 3rem;
            font-size: 2.5rem;
            color: #FFFFFF;
        }

        /* CTA Buttons */
        .cta-button {
            background: linear-gradient(135deg, var(--nasa-blue), var(--jamaica-green));
            padding: 0.6rem 1.2rem;
            border-radius: 30px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            border: 2px solid var(--jamaica-yellow);
            cursor: pointer;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(11, 61, 145, 0.4);
        }

        .cta-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .cta-button:hover::before {
            left: 100%;
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(255, 209, 0, 0.4);
            color: white;
            border-color: var(--jamaica-yellow);
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            #aircraft-container {
                width: 100%;
                height: 300px;
                position: relative;
                margin-top: 2rem;
            }

            .hero {
                flex-direction: column;
                text-align: center;
                padding-top: 100px;
            }

            .hero-content h1 {
                font-size: 2.2rem;
                line-height: 1.2;
            }

            .subtitle {
                font-size: 1rem;
                max-width: 100%;
            }

            .hero-buttons {
                justify-content: center;
                flex-wrap: wrap;
            }
        }

        .hero .hero-content {
            margin-top: -120px !important;
            /* Adjust -120px as needed */
        }

        /* About Us Section */
        .about-section {
            padding: 5rem 5%;
            background: rgba(10, 10, 20, 0.8);
            position: relative;
        }

        .about-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }

        .about-content {
            z-index: 10;
        }

        .about-title {
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            color: #FFFFFF;
        }

        .about-text {
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 2rem;
            opacity: 1;
            color: #FFFFFF;
        }

        .about-features {
            list-style: none;
            padding: 0;
            color: #FFFFFF;
        }

        .about-features li {
            margin-bottom: 1rem;
            padding-left: 2rem;
            position: relative;
        }

        .about-features li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: var(--jamaica-green);
            font-weight: bold;
            font-size: 1.2rem;
        }

        .about-image {
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(11, 61, 145, 0.3);
        }

        .about-image img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .about-image:hover img {
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .about-container {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .about-title {
                font-size: 2rem;
            }

            .about-image img {
                height: 300px;
            }
        }

        /* Join Our Virtual Airline Section */
        .join-section {
            padding: 5rem 5%;
            background: rgba(10, 10, 20, 0.8);
            position: relative;
        }

        .join-container {
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
        }

        .join-title {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #FFFFFF;
        }

        .join-subtitle {
            font-size: 1.2rem;
            margin-bottom: 3rem;
            opacity: 1;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            color: #FFFFFF;
        }

        .join-benefits {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin: 3rem 0;
        }

        .benefit-card {
            background: rgba(30, 30, 45, 0.8);
            border-radius: 15px;
            padding: 2rem;
            transition: all 0.3s ease;
            border: 1px solid rgba(11, 61, 145, 0.3);
            backdrop-filter: blur(10px);
        }

        .benefit-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(11, 61, 145, 0.2);
            border-color: var(--jamaica-green);
        }

        .benefit-icon {
            font-size: 3rem;
            color: var(--jamaica-green);
            margin-bottom: 1rem;
        }

        .benefit-title {
            font-size: 1.3rem;
            margin-bottom: 1rem;
            color: #FFFFFF;
        }

        .benefit-description {
            font-size: 1rem;
            opacity: 1;
            line-height: 1.6;
            color: #FFFFFF;
        }

        .join-cta {
            margin-top: 3rem;
        }

        .join-cta .cta-button {
            font-size: 1.2rem;
            padding: 1rem 2rem;
            margin: 0 1rem;
        }

        @media (max-width: 768px) {
            .join-title {
                font-size: 2rem;
            }

            .join-benefits {
                grid-template-columns: 1fr;
            }

            .join-cta .cta-button {
                display: block;
                margin: 1rem auto;
                max-width: 250px;
            }
        }

        /* Our Fleet Section */
        .fleet-section {
            padding: 5rem 5%;
            background: rgba(10, 10, 20, 0.8);
            position: relative;
        }

        .fleet-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .fleet-title {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #FFFFFF;
        }

        .fleet-subtitle {
            text-align: center;
            font-size: 1.2rem;
            margin-bottom: 3rem;
            opacity: 1;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            color: #FFFFFF;
        }

        .fleet-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin: 3rem 0;
        }

        .fleet-card {
            background: rgba(30, 30, 45, 0.8);
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid rgba(11, 61, 145, 0.3);
            backdrop-filter: blur(10px);
        }

        .fleet-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(11, 61, 145, 0.3);
            border-color: var(--jamaica-green);
        }

        .fleet-image {
            width: 100%;
            height: 250px;
            overflow: hidden;
            position: relative;
        }

        .fleet-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .fleet-card:hover .fleet-image img {
            transform: scale(1.1);
        }

        .fleet-content {
            padding: 2rem;
        }

        .fleet-name {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: var(--nasa-white);
        }

        .fleet-specs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .spec-item {
            display: flex;
            align-items: center;
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .spec-icon {
            color: var(--jamaica-green);
            margin-right: 0.5rem;
            width: 20px;
        }

        .fleet-description {
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
            opacity: 1;
            color: #FFFFFF;
        }

        .fleet-cta {
            text-align: center;
        }

        .fleet-cta .cta-button {
            font-size: 0.9rem;
            padding: 0.8rem 1.5rem;
        }

        @media (max-width: 768px) {
            .fleet-title {
                font-size: 2rem;
            }

            .fleet-grid {
                grid-template-columns: 1fr;
            }

            .fleet-specs {
                grid-template-columns: 1fr;
            }
        }

        /* Live Flight Tracker Section */
        .flight-tracker-section {
            padding: 5rem 5% 3rem 5%;
            background: rgba(10, 10, 20, 0.9);
            position: relative;
        }

        .tracker-container {
            max-width: 1300px;
            margin: 0 auto;
        }

        .tracker-title {
            text-align: center;
            font-size: 2.7rem;
            margin-bottom: 1rem;
            background: linear-gradient(45deg, var(--jamaica-green), var(--jamaica-yellow));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            letter-spacing: 1px;
        }

        .tracker-subtitle {
            text-align: center;
            font-size: 1.2rem;
            margin-bottom: 3rem;
            opacity: 0.9;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .tracker-flex {
            display: flex;
            gap: 2.5rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .globe-card {
            flex: 1 1 400px;
            min-width: 340px;
            max-width: 500px;
            background: rgba(30, 30, 45, 0.7);
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.25);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 420px;
        }

        .globe-gradient {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 60% 40%, var(--jamaica-green) 0%, transparent 70%), radial-gradient(circle at 30% 70%, var(--jamaica-yellow) 0%, transparent 80%);
            opacity: 0.18;
            z-index: 1;
        }

        #globe-canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 2;
            border-radius: 20px;
        }

        #flight-map {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 2;
            border-radius: 20px;
            background: linear-gradient(135deg, #1a73e8, #34a853);
        }

        .map-overlay {
            position: absolute;
            top: 1rem;
            right: 1rem;
            z-index: 3;
        }

        .map-controls {
            display: flex;
            gap: 0.5rem;
        }

        .map-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(30, 30, 45, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: var(--jamaica-green);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .map-btn:hover {
            background: rgba(0, 166, 81, 0.2);
            border-color: var(--jamaica-green);
            transform: scale(1.1);
        }

        .flights-card {
            flex: 2 1 500px;
            min-width: 340px;
            background: rgba(30, 30, 45, 0.7);
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.25);
            display: flex;
            flex-direction: column;
            min-height: 420px;
            overflow: hidden;
        }

        .flights-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 2rem 2rem 1rem 2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.07);
        }

        .flights-title {
            font-size: 1.3rem;
            color: var(--jamaica-green);
            font-weight: 600;
            letter-spacing: 1px;
        }

        .flights-count {
            font-size: 1rem;
            color: #fff;
            opacity: 0.7;
        }

        .flights-list {
            flex: 1;
            overflow-y: auto;
            padding: 1rem 2rem 1rem 2rem;
            max-height: 260px;
        }

        .flight-row {
            display: flex;
            align-items: flex-start;
            background: rgba(255, 255, 255, 0.04);
            border-radius: 12px;
            margin-bottom: 1.2rem;
            padding: 1rem 1.2rem;
            box-shadow: 0 2px 8px 0 rgba(11, 61, 145, 0.07);
            border-left: 4px solid var(--jamaica-green);
            transition: background 0.2s;
        }

        .flight-row:last-child {
            margin-bottom: 0;
        }

        .flight-badge {
            font-size: 0.8rem;
            font-weight: 700;
            padding: 0.3rem 0.8rem;
            border-radius: 12px;
            margin-right: 1.2rem;
            margin-top: 0.2rem;
            letter-spacing: 1px;
            color: #fff;
            box-shadow: 0 2px 8px 0 rgba(11, 61, 145, 0.07);
        }

        .badge-enroute {
            background: linear-gradient(90deg, var(--jamaica-green), #34a853);
        }

        .badge-boarding {
            background: linear-gradient(90deg, #FED100, #b5d500);
            color: #222;
        }

        .badge-arrived {
            background: linear-gradient(90deg, #0B3D91, #1a73e8);
        }

        .flight-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 120px;
        }

        .flight-num {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--jamaica-yellow);
            margin-bottom: 0.2rem;
        }

        .flight-route {
            font-size: 0.95rem;
            color: #fff;
            opacity: 0.85;
        }

        .flight-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.7rem 1.2rem;
            font-size: 0.92rem;
            color: #fff;
            opacity: 0.8;
            margin-left: 1.5rem;
            align-items: center;
        }

        .flights-summary-bar {
            display: flex;
            justify-content: space-around;
            align-items: center;
            background: rgba(255, 255, 255, 0.06);
            padding: 1.2rem 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.07);
        }

        .summary-item {
            text-align: center;
        }

        .summary-num {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--jamaica-green);
            margin-bottom: 0.2rem;
            display: block;
        }

        .summary-label {
            font-size: 0.95rem;
            color: #fff;
            opacity: 0.8;
        }

        @media (max-width: 1100px) {
            .tracker-flex {
                flex-direction: column;
            }

            .globe-card,
            .flights-card {
                max-width: 100%;
            }
        }

        @media (max-width: 700px) {
            .flight-tracker-section {
                padding: 2.5rem 0.5rem;
            }

            .tracker-title {
                font-size: 2rem;
            }

            .flights-header,
            .flights-list,
            .flights-summary-bar {
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }
    </style>
@endpush

@section('content')
    <div id="vanta-globe"></div>

    <!-- Hero Section (World-Class Aviation) -->
    <section class="hero" id="home">

        <div class="hero-content" style="margin-top: -120px;">
            <!-- Air Jamaica Virtual Logo -->
            <div class="airline-branding" style="display: flex; align-items: center; margin-bottom: 2rem;">
                <img src="{{ public_asset('/assets/img/air jamaica logo copy.png') }}" alt="Air Jamaica Virtual"
                    style="height: 80px; width: auto; margin-right: 1.5rem; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));">
                <div>
                    <h2 style="font-size: 2.2rem; margin: 0; color: #00A651; font-weight: 700;">AIR JAMAICA</h2>
                    <h3 style="font-size: 1.4rem; margin: 0; color: #FED100; font-weight: 600;">VIRTUAL AIRLINES</h3>
                </div>
            </div>

            <h1>EXPERIENCE JAMAICA'S<br>VIRTUAL SKIES</h1>
            <p class="subtitle" style="position: relative; z-index: 10;">Welcome to Air Jamaica Virtual - The Caribbean's
                premier virtual airline. Fly the beautiful islands of Jamaica and beyond with our modern fleet. Join our
                passionate community of virtual pilots and experience authentic Caribbean aviation excellence.</p>

            <div class="hero-buttons">
                @guest
                    <a href="{{ url('/register') }}" class="btn-cyber btn-cyber-primary">
                        Join Operations
                    </a>
                    <a href="{{ url('/login') }}" class="btn-cyber btn-cyber-secondary">
                        Pilot Portal
                    </a>
                @else
                    <a href="{{ route('frontend.dashboard.index') }}" class="btn-cyber btn-cyber-primary">
                        Cockpit
                    </a>
                    <a href="{{ route('frontend.flights.index') }}" class="btn-cyber btn-cyber-secondary">
                        Flight Board
                    </a>
                @endguest
            </div>

        </div>
        </div>

        <!-- Hero Slider Display (Holographic Prism) -->
        <div id="hero-slider-container">
            <div class="holo-card" data-tilt data-tilt-max="15" data-tilt-speed="400" data-tilt-glare
                data-tilt-max-glare="0.5">
                <div class="holo-overlay"></div>
                <div class="scan-line"></div>

                <!-- Corner Accents -->
                <div class="holo-border-corner tl"></div>
                <div class="holo-border-corner tr"></div>
                <div class="holo-border-corner bl"></div>
                <div class="holo-border-corner br"></div>

                <div class="holo-image-container">
                    <div class="holo-slide active" data-title="Boeing 737-800" data-range="3,000 nm" data-speed="Mach 0.82">
                        <img src="{{ public_asset('/assets/img/plane1.jpg') }}" alt="Boeing 737-800">
                    </div>
                    <div class="holo-slide" data-title="Airbus A320neo" data-range="3,500 nm" data-speed="Mach 0.82">
                        <img src="{{ public_asset('/assets/img/slide3.jpeg') }}" alt="Airbus A320neo">
                    </div>
                    <div class="holo-slide" data-title="Boeing 787-9" data-range="7,635 nm" data-speed="Mach 0.85">
                        <img src="{{ public_asset('/assets/img/slide8.jpeg') }}" alt="Boeing 787-9">
                    </div>
                    <div class="holo-slide" data-title="Airbus A350-900" data-range="8,100 nm" data-speed="Mach 0.89">
                        <img src="{{ public_asset('/assets/img/plane7.jpg') }}" alt="Airbus A350-900">
                    </div>
                    <div class="holo-slide" data-title="A340-300" data-range="7,400 nm" data-speed="Mach 0.82">
                        <img src="{{ public_asset('/assets/img/hero_slide1.jpeg') }}" alt="A340-300">
                    </div>
                    <div class="holo-slide" data-title="A310" data-range="4,650 nm" data-speed="Mach 0.80">
                        <img src="{{ public_asset('/assets/img/hero_slide2.jpeg') }}" alt="A310">
                    </div>
                    <div class="holo-slide" data-title="A320" data-range="3,300 nm" data-speed="Mach 0.82">
                        <img src="{{ public_asset('/assets/img/hero_slide3.jpeg') }}" alt="A320">
                    </div>
                    <div class="holo-slide" data-title="A321" data-range="3,200 nm" data-speed="Mach 0.82">
                        <img src="{{ public_asset('/assets/img/hero_slide6.jpeg') }}" alt="A321">
                    </div>
                </div>
            </div>
        </div>




    </section>

    <!-- About Us Section -->
    <section class="about-section" id="about">
        <div class="about-container">
            <div class="about-content">
                <h2 class="about-title">About Us</h2>
                <p class="about-text">
                    We are the global leader in virtual aviation excellence, setting the gold standard for flight simulation
                    worldwide. Our mission is to deliver an unparalleled, hyper-realistic, and community-driven experience
                    for virtual pilots across every continent. With revolutionary technology, worldwide route networks, and
                    an unwavering commitment to perfection, we unite aviation professionals and enthusiasts from around the
                    globe.
                </p>
                <ul class="about-features">
                    <li>Comprehensive global route network spanning all six continents</li>
                    <li>Advanced real-time flight tracking with cutting-edge live events</li>
                    <li>World-class international pilot community and 24/7 professional support</li>
                </ul>
            </div>
            <div class="about-image">
                <img src="{{ public_asset('/assets/img/slide2.jpeg') }}" alt="Air Jamaica Virtual Airlines" />
            </div>
        </div>
    </section>

    <!-- Join Our Virtual Airline Section -->
    <section class="join-section" id="join">
        <div class="join-container">
            <h2 class="join-title">Join the World's Premier Virtual Airline</h2>
            <p class="join-subtitle">Become part of the most elite virtual airline network on Earth. Experience
                professional-grade flight simulation, explore global destinations from New York to Tokyo, and connect with
                an exclusive community of aviation professionals and enthusiasts worldwide.</p>

            <div class="join-benefits">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="fas fa-globe-americas"></i>
                    </div>
                    <h3 class="benefit-title">Global Route Network</h3>
                    <p class="benefit-description">Fly to over 500 destinations worldwide including major hubs like London
                        Heathrow, Dubai International, and Singapore Changi.</p>
                </div>

                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <h3 class="benefit-title">Cutting-Edge Technology</h3>
                    <p class="benefit-description">Experience the most advanced flight simulation technology with real-world
                        weather, live air traffic, and precision navigation systems.</p>
                </div>

                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <h3 class="benefit-title">Elite Pilot Community</h3>
                    <p class="benefit-description">Join pilots from every continent in competitions, group flights, and
                        exclusive events. Network with aviation professionals worldwide.</p>
                </div>

                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="fas fa-fighter-jet"></i>
                    </div>
                    <h3 class="benefit-title">World-Class Fleet</h3>
                    <p class="benefit-description">Pilot the latest aircraft from Boeing, Airbus, and Embraer with
                        ultra-realistic flight dynamics and systems simulation.</p>
                </div>
            </div>

            <div class="join-cta">
                @guest
                    <a href="{{ url('/register') }}" class="cta-button">
                        <i class="fas fa-rocket"></i> Join Elite Squadron
                    </a>
                    <a href="{{ url('/login') }}" class="cta-button"
                        style="background: transparent; border: 2px solid var(--jamaica-green);">
                        <i class="fas fa-sign-in-alt"></i> Access Portal
                    </a>
                @else
                    <a href="{{ route('frontend.dashboard.index') }}" class="cta-button">
                        <i class="fas fa-tachometer-alt"></i> Command Center
                    </a>
                    <a href="{{ route('frontend.flights.index') }}" class="cta-button"
                        style="background: transparent; border: 2px solid var(--jamaica-green);">
                        <i class="fas fa-plane"></i> Explore Fleet
                    </a>
                @endguest
            </div>
        </div>
    </section>

    <!-- Our Fleet Section -->
    <section class="fleet-section" id="fleet">
        <div class="fleet-container">
            <h2 class="fleet-title">Our World-Class Fleet</h2>
            <p class="fleet-subtitle">
                Discover our state-of-the-art aircraft fleet, featuring the latest technology and premium comfort for global
                destinations. Each aircraft is meticulously maintained to airline industry standards.
            </p>
            <div class="fleet-grid">
                <div class="fleet-card">
                    <div class="fleet-image">
                        <img src="{{ public_asset('/assets/img/plane1.jpg') }}" alt="Premium Aircraft Fleet" />
                    </div>
                    <div class="fleet-content">
                        <h3 class="fleet-name">Premium Fleet Collection</h3>
                        <p class="fleet-description">Experience unmatched comfort and reliability with our cutting-edge
                            aircraft, engineered for global excellence and precision performance.</p>
                    </div>
                </div>
                <div class="fleet-card">
                    <div class="fleet-image">
                        <img src="{{ public_asset('/assets/img/plane2.jpg') }}" alt="Advanced Aviation Technology" />
                    </div>
                    <div class="fleet-content">
                        <h3 class="fleet-name">Next-Generation Aircraft</h3>
                        <p class="fleet-description">Fly with absolute confidence using our technologically advanced and
                            rigorously maintained aircraft, serving destinations across six continents.</p>
                    </div>
                </div>
                <div class="fleet-card">
                    <div class="fleet-image">
                        <img src="{{ public_asset('/assets/img/plane3.jpg') }}" alt="Professional Aviation Standards" />
                    </div>
                    <div class="fleet-content">
                        <h3 class="fleet-name">Elite Aviation Standards</h3>
                        <p class="fleet-description">Experience the pinnacle of virtual aviation with our dedicated crew
                            excellence and industry-leading onboard technology systems.</p>
                    </div>
                </div>
            </div>
            <div style="text-align:center; margin-top:2rem;">
                <a href="{{ route('frontend.flights.index') }}" class="cta-button" style="font-size:1.1rem;">
                    View All Aircraft
                </a>
            </div>
        </div>
    </section>

    <!-- Live Flight Tracker Section -->
    <section class="flight-tracker-section" id="flight-tracker">
        <div class="tracker-container">
            <h2 class="tracker-title">Global Flight Operations Center</h2>
            <p class="tracker-subtitle">Monitor our virtual airline's worldwide operations in real-time. Track flights
                spanning every continent, see our pilots navigating global routes, and witness the pulse of our
                international network!</p>
            <div class="tracker-flex">
                <div class="globe-card">
                    <div class="globe-gradient"></div>
                    <div id="flight-map"></div>
                    <div class="map-overlay">
                        <div class="map-controls">
                            <button class="map-btn" id="reset-map"><i class="fas fa-home"></i></button>
                            <button class="map-btn" id="toggle-flights"><i class="fas fa-plane"></i></button>
                        </div>
                    </div>
                </div>
                <div class="flights-card">
                    <div class="flights-header">
                        <span class="flights-title"><i class="fas fa-plane-departure"></i> Active Flights</span>
                        <span class="flights-count" id="flights-count">0 Online</span>
                    </div>
                    <div class="flights-list" id="flights-list">
                        <div class="flight-row loading-row">Loading flights...</div>
                    </div>
                    <div class="flights-summary-bar">
                        <div class="summary-item">
                            <span class="summary-num" id="summary-flights">0</span>
                            <span class="summary-label">Flights Online</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-num" id="summary-pilots">0</span>
                            <span class="summary-label">Pilots</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-num" id="summary-destinations">0</span>
                            <span class="summary-label">Destinations</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics & Achievements Section -->
    <section class="stats-section" id="stats">
        <div class="stats-container">
            <div class="stats-header">
                <h2 class="stats-title">Global Excellence Metrics</h2>
                <p class="stats-subtitle">Discover the impressive achievements that establish us as the world's premier
                    virtual airline, connecting pilots and destinations across every continent.</p>
            </div>
            @php
                // Dynamic stats from Air Jamaica Virtual data
                $flightsCompleted = \App\Models\Pirep::where('state', \App\Models\Enums\PirepState::ACCEPTED)->count();
                $activePilots = \App\Models\User::where('state', \App\Models\Enums\UserState::ACTIVE)->count();
                $destinations = \App\Models\Airport::count();
            @endphp
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-plane"></i>
                    </div>
                    <div class="stat-number" data-target="{{ $flightsCompleted }}">0</div>
                    <div class="stat-label">Flights Completed</div>
                    <div class="stat-description">Successful global flights spanning six continents</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-number" data-target="{{ $activePilots }}">0</div>
                    <div class="stat-label">Active Pilots</div>
                    <div class="stat-description">Elite virtual pilots from around the world</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="stat-number" data-target="{{ $destinations }}">0</div>
                    <div class="stat-label">Global Destinations</div>
                    <div class="stat-description">Major international airports and hubs worldwide</div>
                </div>
            </div>
            <div class="achievements-showcase">
                <div class="achievement-item">
                    <div class="achievement-icon">
                        <i class="fas fa-medal"></i>
                    </div>
                    <div class="achievement-content">
                        <h3>World's Premier Virtual Airline 2024</h3>
                        <p>Awarded by International Virtual Aviation Organization for outstanding global service and
                            technological innovation</p>
                    </div>
                </div>
                <div class="achievement-item">
                    <div class="achievement-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="achievement-content">
                        <h3>Platinum Safety Excellence Rating</h3>
                        <p>Consistently maintaining the highest safety standards in virtual aviation operations across all
                            global routes</p>
                    </div>
                </div>
                <div class="achievement-item">
                    <div class="achievement-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <div class="achievement-content">
                        <h3>Global Community Leadership Award</h3>
                        <p>Recognized for fostering the world's most inclusive and professional international pilot
                            community</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.globe.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.0/vanilla-tilt.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            // Initialize Vanilla Tilt manually if needed, though data-tilt attr handles it mostly
            VanillaTilt.init(document.querySelector(".holo-card"), {
                max: 15,
                speed: 400,
                glare: true,
                "max-glare": 0.5,
            });

            // Holographic Slide Logic (slides only; HUD overlay removed)
            const slides = document.querySelectorAll('.holo-slide');
            let currentSlide = 0;

            function nextSlide() {
                slides[currentSlide].classList.remove('active');
                currentSlide = (currentSlide + 1) % slides.length;
                slides[currentSlide].classList.add('active');
            }

            setInterval(nextSlide, 5000);

            // Existing Vanta Globe Init
            const vantaEffect = VANTA.GLOBE({

                el: "#vanta-globe",
                mouseControls: true,
                touchControls: true,
                gyroControls: false,
                minHeight: 200.00,
                minWidth: 200.00,
                scale: 1.00,
                scaleMobile: 1.50, // Increase scale on mobile to center the globe's curve
                color: 0xFED100, // Air Jamaica Yellow
                backgroundColor: 0x0A0A15, // Deep Space Dark
                points: 12.00,
                maxDistance: 22.00,
                spacing: 16.00,
            });

            window.addEventListener('resize', () => {
                if (vantaEffect) vantaEffect.resize();
            });



            // Initialize Leaflet map
            const map = L.map('flight-map', {
                center: [17.9356, -76.7875], // Jamaica default
                zoom: 6,
                zoomControl: true,
                attributionControl: false
            });
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            let aircraftMarkers = [];

            function clearAircraftMarkers() {
                aircraftMarkers.forEach(marker => map.removeLayer(marker));
                aircraftMarkers = [];
            }

            function addAircraftMarkers(flights) {
                clearAircraftMarkers();
                flights.forEach(flight => {
                    if (flight.lat && flight.lon) {
                        const marker = L.marker([flight.lat, flight.lon], {
                            title: flight.flight_id,
                            icon: L.divIcon({
                                className: 'aircraft-icon',
                                html: `<i class=\"fas fa-plane\" style=\"color:#00A651;font-size:22px;\"></i>`,
                                iconSize: [22, 22],
                                iconAnchor: [11, 11]
                            })
                        }).addTo(map);
                        marker.bindPopup(`
                                            <strong>${flight.flight_id}</strong><br>
                                            Pilot: ${flight.pilot_name}<br>
                                            Route: ${flight.dpt_airport_id} → ${flight.arr_airport_id}<br>
                                            Altitude: ${flight.altitude || 0} ft<br>
                                            Speed: ${flight.gs || 0} kts
                                        `);
                        aircraftMarkers.push(marker);
                    }
                });
            }

            // Normalize API flight (Laravel PirepResource) to flat shape for list and map
            function normalizeFlight(apiFlight) {
                const pos = apiFlight.position || {};
                const dpt = apiFlight.dpt_airport || {};
                const arr = apiFlight.arr_airport || {};
                const user = apiFlight.user || {};
                const aircraft = apiFlight.aircraft || {};
                return {
                    lat: pos.lat,
                    lon: pos.lon,
                    flight_id: apiFlight.ident || apiFlight.flight_number || 'N/A',
                    pilot_name: user.name_private || user.name || 'N/A',
                    dpt_airport_id: dpt.icao || apiFlight.dpt_airport_id || 'N/A',
                    arr_airport_id: arr.icao || apiFlight.arr_airport_id || 'N/A',
                    altitude: pos.altitude != null ? pos.altitude : 0,
                    gs: pos.gs != null ? pos.gs : 0,
                    aircraft_name: aircraft.registration || aircraft.name || aircraft.icao || 'N/A'
                };
            }

            async function fetchFlights() {
                const flightsList = document.getElementById('flights-list');
                const flightsCount = document.getElementById('flights-count');
                const summaryFlights = document.getElementById('summary-flights');
                const summaryPilots = document.getElementById('summary-pilots');
                const summaryDestinations = document.getElementById('summary-destinations');

                try {
                    const response = await fetch('{{ url("/api/acars") }}');
                    if (!response.ok) throw new Error('API error');
                    const json = await response.json();
                    // Laravel API resource returns { data: [ ... ] }
                    const rawFlights = Array.isArray(json.data) ? json.data : (Array.isArray(json) ? json : []);
                    const flights = rawFlights.map(normalizeFlight).filter(f => f.lat != null && f.lon != null);

                    if (flights.length === 0) {
                        flightsList.innerHTML = '<div class="flight-row loading-row">No active flights</div>';
                        flightsCount.textContent = '0 Online';
                        summaryFlights.textContent = '0';
                        summaryPilots.textContent = '0';
                        summaryDestinations.textContent = '0';
                        clearAircraftMarkers();
                        return;
                    }

                    const pilots = new Set();
                    const destinations = new Set();
                    let html = '';

                    flights.forEach(flight => {
                        if (flight.pilot_name) pilots.add(flight.pilot_name);
                        if (flight.arr_airport_id) destinations.add(flight.arr_airport_id);

                        html += `<div class="flight-row">
                                            <div class="flight-badge badge-enroute">ENROUTE</div>
                                            <div class="flight-main">
                                                <span class="flight-num">${flight.flight_id || 'N/A'}</span>
                                                <span class="flight-route">${flight.dpt_airport_id || 'N/A'} <i class="fas fa-arrow-right"></i> ${flight.arr_airport_id || 'N/A'}</span>
                                            </div>
                                            <div class="flight-meta">
                                                <span><i class="fas fa-user"></i> ${flight.pilot_name || 'N/A'}</span>
                                                <span><i class="fas fa-plane"></i> ${flight.aircraft_name || 'N/A'}</span>
                                                <span><i class="fas fa-tachometer-alt"></i> ${flight.gs || 0} kts</span>
                                                <span><i class="fas fa-arrow-up"></i> ${flight.altitude || 0} ft</span>
                                            </div>
                                        </div>`;
                    });

                    flightsList.innerHTML = html;
                    flightsCount.textContent = flights.length + ' Online';
                    summaryFlights.textContent = flights.length;
                    summaryPilots.textContent = pilots.size;
                    summaryDestinations.textContent = destinations.size;

                    addAircraftMarkers(flights);
                } catch (e) {
                    flightsList.innerHTML = '<div class="flight-row loading-row">Error loading flights. Retrying...</div>';
                    clearAircraftMarkers();
                }
            }

            fetchFlights();
            setInterval(fetchFlights, 30000); // Refresh every 30 seconds

            // Map controls: reset view to Jamaica default
            document.getElementById('reset-map').addEventListener('click', function() {
                map.setView([17.9356, -76.7875], 6);
            });

            // Toggle flight markers visibility
            let flightsVisible = true;
            document.getElementById('toggle-flights').addEventListener('click', function() {
                flightsVisible = !flightsVisible;
                aircraftMarkers.forEach(marker => {
                    if (flightsVisible) map.addLayer(marker); else map.removeLayer(marker);
                });
            });

            // When Global Flight Operations section becomes visible, refresh map size (Leaflet needs this if container was hidden)
            const trackerSection = document.getElementById('flight-tracker');
            if (trackerSection && typeof ResizeObserver !== 'undefined') {
                const ro = new ResizeObserver(() => { if (map && map.invalidateSize) map.invalidateSize(); });
                ro.observe(document.getElementById('flight-map'));
            }
        });
    </script>
    <script>
        // Statistics Counter Animation
        function animateCounter(element, target, duration = 2000) {
            let start = 0;
            const increment = target / (duration / 16);

            function updateCounter() {
                start += increment;
                if (start < target) {
                    element.textContent = Math.floor(start);
                    requestAnimationFrame(updateCounter);
                } else {
                    element.textContent = target;
                }
            }

            updateCounter();
        }

        // Intersection Observer for Statistics Animation
        const statsSection = document.querySelector('.stats-section');
        const statNumbers = document.querySelectorAll('.stat-number');
        let animated = false;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !animated) {
                    animated = true;
                    statNumbers.forEach(stat => {
                        const target = parseInt(stat.getAttribute('data-target'));
                        animateCounter(stat, target);
                    });
                }
            });
        }, { threshold: 0.5 });

        if (statsSection) {
            observer.observe(statsSection);
        }

        // Smooth Scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });


    </script>
@endpush
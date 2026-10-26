import React from 'react';
import { Helmet } from 'react-helmet';
import Header from '@/components/Header';
import Hero from '@/components/Hero';
import CompanyProfile from '@/components/CompanyProfile';
import Services from '@/components/Services';
import GordynExpertise from '@/components/GordynExpertise';
import HotelExpertise from '@/components/HotelExpertise';
import Pricing from '@/components/Pricing';
import ContactUs from '@/components/ContactUs';
import Feedback from '@/components/Feedback';
import Footer from '@/components/Footer';
import WhatsAppButton from '@/components/WhatsAppButton';
import { Toaster } from '@/components/ui/toaster';

function App() {
  return (
    <>
      <Helmet>
        <title>CROWN & LUCKY Laundry - Mitra Laundry Profesional Sejak 1997</title>
        <meta name="description" content="CROWN & LUCKY Laundry: berpengalaman sejak 1997. Kami menyediakan layanan cuci, setrika, dry cleaning, dan perbaikan gordyn dengan layanan cepat dan andal. Mitra terpercaya untuk laundry hotel dan pribadi." />
      </Helmet>
      <div className="min-h-screen bg-background">
        <Header />
        <main>
          <Hero />
          <CompanyProfile />
          <Services />
          <HotelExpertise />
          <GordynExpertise />
          <Pricing />
          <ContactUs />
          <Feedback />
        </main>
        <Footer />
        <WhatsAppButton />
        <Toaster />
      </div>
    </>
  );
}

export default App;
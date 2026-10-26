import React from 'react';
import { motion } from 'framer-motion';
import { Shirt, Wind, Sparkles, Truck } from 'lucide-react';

const Title = ({ children }) => (
  <motion.h2 
    initial={{ opacity: 0, y: 20 }}
    whileInView={{ opacity: 1, y: 0 }}
    viewport={{ once: true, amount: 0.5 }}
    transition={{ duration: 0.5 }}
    className="text-4xl md:text-5xl font-serif font-bold mb-4"
  >
    {children}
  </motion.h2>
);

const Services = () => {
  const services = [
    {
      icon: Shirt,
      title: 'Cuci & Setrika',
      description: 'Pakaian dicuci bersih dan disetrika rapi dengan standar profesional.',
      bg: 'bg-green-100/50',
    },
    {
      icon: Wind,
      title: 'Dry Cleaning',
      description: 'Perawatan khusus untuk pakaian berbahan sensitif seperti jas dan gaun.',
      bg: 'bg-emerald-100/50',
    },
    {
      icon: Sparkles,
      title: 'Layanan Cepat',
      description: 'Layanan kilat untuk kebutuhan mendesak Anda, selesai di hari yang sama.',
      bg: 'bg-teal-100/50',
    },
    {
      icon: Truck,
      title: 'Antar Jemput',
      description: 'Layanan antar jemput gratis untuk kemudahan dan kenyamanan Anda.',
      bg: 'bg-lime-100/50',
    },
  ];

  return (
    <section id="services" className="py-20 px-4 section-bg water-effect" style={{'--ripple-delay': '1s'}}>
      <div className="container mx-auto">
        <div className="text-center mb-16">
          <Title>
            Layanan <span className="bg-gradient-to-r from-primary to-emerald-600 bg-clip-text text-transparent">Terbaik</span> Kami
          </Title>
          <motion.p 
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, amount: 0.5 }}
            transition={{ duration: 0.5, delay: 0.2 }}
            className="text-xl text-muted-foreground max-w-2xl mx-auto"
          >
            Pilihan layanan lengkap untuk memenuhi semua kebutuhan kebersihan pakaian Anda.
          </motion.p>
        </div>

        <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
          {services.map((service, index) => (
            <motion.div
              key={index}
              initial={{ opacity: 0, y: 30 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ delay: index * 0.1 }}
              whileHover={{ y: -10 }}
              className={`relative rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border overflow-hidden ${service.bg}`}
            >
              <div className="absolute inset-0 opacity-20" style={{ backgroundImage: `url('/pattern.svg')`, backgroundSize: 'cover' }}></div>
              <div className="relative z-10">
                <motion.div 
                  whileHover={{ scale: 1.1, rotate: 10 }}
                  transition={{ type: 'spring', stiffness: 300 }}
                  className={`w-16 h-16 bg-gradient-to-br from-primary to-emerald-500 rounded-2xl flex items-center justify-center mb-6`}
                >
                  <service.icon className="w-8 h-8 text-white" />
                </motion.div>
                <h3 className="text-xl font-bold mb-3 text-foreground">{service.title}</h3>
                <p className="text-muted-foreground leading-relaxed">{service.description}</p>
              </div>
            </motion.div>
          ))}
        </div>
      </div>
    </section>
  );
};

export default Services;
import React, { useState } from 'react';
import { motion } from 'framer-motion';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { toast } from '@/components/ui/use-toast';
import { Phone, Mail, MapPin } from 'lucide-react';

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

const ContactUs = () => {
  const [formData, setFormData] = useState({
    name: '',
    phone: '',
    message: '',
  });

  const handleSubmit = (e) => {
    e.preventDefault();
    
    if (!formData.name || !formData.phone || !formData.message) {
      toast({
        title: '⚠️ Form Belum Lengkap',
        description: 'Mohon lengkapi semua field yang wajib diisi.',
        variant: 'destructive',
      });
      return;
    }
    
    toast({
        title: '🚧 Fitur Belum Terimplementasi',
        description: "Saat ini, fitur pengiriman pesan sedang dalam pengembangan. Silakan hubungi kami melalui WhatsApp.",
    });
  };

  const contactInfo = [
    { icon: Phone, text: '+62 822 3377 59394' },
    { icon: Phone, text: '(+6231) 867 9336' },
    { icon: Mail, text: 'crownlaundry.service@gmail.com' },
    { icon: MapPin, text: 'Jl. Abd. Rahman BLK No. 98 Pabean, Sedati, Sidoarjo' },
  ];

  return (
    <section id="contact" className="py-20 px-4 section-bg bg-secondary/30 water-effect" style={{'--ripple-delay': '5s'}}>
      <div className="container mx-auto">
        <div className="text-center mb-12">
          <Title>
            Hubungi <span className="bg-gradient-to-r from-primary to-emerald-600 bg-clip-text text-transparent">Kami</span>
          </Title>
          <motion.p 
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, amount: 0.5 }}
            transition={{ duration: 0.5, delay: 0.2 }}
            className="text-xl text-muted-foreground"
          >
            Ada pertanyaan atau ingin memesan layanan? Isi form di bawah ini atau hubungi kami langsung.
          </motion.p>
        </div>

        <div className="grid md:grid-cols-2 gap-12 max-w-5xl mx-auto">
          <motion.div
            initial={{ opacity: 0, y: 30 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            className="bg-card rounded-3xl p-8 md:p-10 shadow-2xl border"
          >
            <form onSubmit={handleSubmit} className="space-y-6">
              <div>
                <Label htmlFor="name" className="text-foreground font-semibold">Nama Lengkap *</Label>셔
                <Input
                  id="name"
                  type="text"
                  value={formData.name}
                  onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                  className="mt-2 border-2 focus:border-primary"
                  placeholder="Masukkan nama Anda"
                />
              </div>

              <div>
                <Label htmlFor="phone" className="text-foreground font-semibold">Nomor Telepon *</Label>
                <Input
                  id="phone"
                  type="tel"
                  value={formData.phone}
                  onChange={(e) => setFormData({ ...formData, phone: e.target.value })}
                  className="mt-2 border-2 focus:border-primary"
                  placeholder="Contoh: 081234567890"
                />
              </div>
              
              <div>
                <Label htmlFor="message" className="text-foreground font-semibold">Pesan Anda *</Label>
                <Textarea
                  id="message"
                  value={formData.message}
                  onChange={(e) => setFormData({ ...formData, message: e.target.value })}
                  className="mt-2 border-2 focus:border-primary"
                  placeholder="Tuliskan pertanyaan atau detail pesanan Anda di sini..."
                  rows={4}
                />
              </div>

              <Button
                type="submit"
                className="w-full bg-gradient-to-r from-primary to-emerald-500 hover:opacity-90 text-primary-foreground text-lg py-6"
              >
                Kirim Pesan
              </Button>
            </form>
          </motion.div>
          <motion.div
            initial={{ opacity: 0, y: 30 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, amount: 0.5 }}
            transition={{ delay: 0.2 }}
            className="flex flex-col justify-center space-y-6"
          >
            {contactInfo.map((item, index) => (
              <div key={index} className="flex items-start gap-4">
                <div className="w-12 h-12 bg-secondary rounded-lg flex items-center justify-center flex-shrink-0">
                    <item.icon className="w-6 h-6 text-primary" />
                </div>
                <div>
                  <p className="text-lg font-semibold text-foreground">{item.text}</p>
                </div>
              </div>
            ))}
          </motion.div>
        </div>
      </div>
    </section>
  );
};

export default ContactUs;
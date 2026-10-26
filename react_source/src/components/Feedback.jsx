import React, { useState } from 'react';
import { motion } from 'framer-motion';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { toast } from '@/components/ui/use-toast';
import { MessageSquare, Send } from 'lucide-react';

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

const Feedback = () => {
  const [feedbackData, setFeedbackData] = useState({
    name: '',
    feedback: '',
  });

  const handleSubmit = (e) => {
    e.preventDefault();
    
    if (!feedbackData.name || !feedbackData.feedback) {
      toast({
        title: '⚠️ Form Tidak Lengkap',
        description: 'Mohon isi nama dan masukan Anda.',
        variant: 'destructive',
      });
      return;
    }

    const phoneNumber = "62822337759394";
    const message = `Kritik & Saran dari: ${feedbackData.name}\n\nPesan: ${feedbackData.feedback}`;
    const whatsappUrl = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(message)}`;
    
    window.open(whatsappUrl, '_blank');

    toast({
      title: 'Membuka WhatsApp... 💬',
      description: 'Terima kasih atas masukan Anda! Kami akan segera membacanya.',
    });

    setFeedbackData({ name: '', feedback: '' });
  };

  return (
    <section id="feedback" className="py-20 px-4 section-bg water-effect" style={{'--ripple-delay': '6s'}}>
      <div className="container mx-auto max-w-3xl">
        <div className="text-center mb-12">
          <Title>
            Kritik & <span className="bg-gradient-to-r from-primary to-emerald-600 bg-clip-text text-transparent">Saran</span>
          </Title>
          <motion.p 
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, amount: 0.5 }}
            transition={{ duration: 0.5, delay: 0.2 }}
            className="text-xl text-muted-foreground"
          >
            Masukan Anda sangat berharga untuk kami menjadi lebih baik.
          </motion.p>
        </div>

        <motion.div
          initial={{ opacity: 0, y: 30 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          className="bg-card rounded-3xl p-8 md:p-10 shadow-2xl border"
        >
          <form onSubmit={handleSubmit} className="space-y-6">
            <div>
              <Label htmlFor="feedback-name" className="text-foreground font-semibold">Nama Anda *</Label>
              <Input
                id="feedback-name"
                type="text"
                value={feedbackData.name}
                onChange={(e) => setFeedbackData({ ...feedbackData, name: e.target.value })}
                className="mt-2 border-2 focus:border-primary"
                placeholder="Nama Anda"
              />
            </div>
            
            <div>
              <Label htmlFor="feedback-message" className="text-foreground font-semibold">Kritik atau Saran Anda *</Label>
              <Textarea
                id="feedback-message"
                value={feedbackData.feedback}
                onChange={(e) => setFeedbackData({ ...feedbackData, feedback: e.target.value })}
                className="mt-2 border-2 focus:border-primary"
                placeholder="Tuliskan masukan Anda di sini..."
                rows={5}
              />
            </div>

            <Button
              type="submit"
              className="w-full bg-gradient-to-r from-primary to-emerald-500 hover:opacity-90 text-primary-foreground text-lg py-6 flex items-center gap-2"
            >
              <Send className="w-5 h-5" /> Kirim via WhatsApp
            </Button>
          </form>
        </motion.div>
      </div>
    </section>
  );
};

export default Feedback;
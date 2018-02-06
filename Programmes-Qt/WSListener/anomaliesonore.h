#ifndef ANOMALIESONORE_H
#define ANOMALIESONORE_H

#include <QDateTime>

class AnomalieSonore{

public:
    explicit AnomalieSonore(double niveau, QDateTime date_debut, QDateTime date_fin, unsigned int id_as = 0, bool debug = false);
    explicit AnomalieSonore();
    unsigned int get_id_as() const;
    double get_niveau() const;
    QDateTime get_date_debut() const;
    QDateTime get_date_fin() const;

private:
    unsigned int m_id_as;
    double m_niveau;
    QDateTime m_date_debut;
    QDateTime m_date_fin;
    bool m_debug;
};

#endif // ANOMALIESONORE_H

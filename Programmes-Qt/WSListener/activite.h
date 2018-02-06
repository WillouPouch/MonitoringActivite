#ifndef ACTIVITE_H
#define ACTIVITE_H

#include <QDateTime>

class Activite{

public:
    explicit Activite(QDateTime date_debut, QDateTime date_fin, QString type_activite, unsigned int id_ec = 0, bool debug = false);
    explicit Activite();
    unsigned int get_id_activite() const;
    QDateTime get_date_debut() const;
    QDateTime get_date_fin() const;
    QString get_type_activite() const;

private:
    unsigned int m_id_activite;
    QDateTime m_date_debut;
    QDateTime m_date_fin;
    QString m_type_activite;
    bool m_debug;
};

#endif // ACTIVITE_H

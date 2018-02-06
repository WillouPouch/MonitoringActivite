#ifndef LISTEACTIVITE_H
#define LISTEACTIVITE_H

#include <QtCore>
#include "activite.h"

class ListeActivite : public QObject {
    Q_OBJECT

public:
    explicit ListeActivite(unsigned int nb_record, QObject *parent = Q_NULLPTR, bool debug = false);
    void append_custom(Activite & ec);
    void prepare_json();

private:
     bool m_debug;
     unsigned int m_nb_record;
     QVector<Activite> m_vector;

signals:
    void act_json_ready(QJsonDocument);

};

#endif //LISTEACTIVITE_H

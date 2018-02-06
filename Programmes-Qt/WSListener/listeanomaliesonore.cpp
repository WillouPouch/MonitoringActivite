#include "listeanomaliesonore.h"

ListeAnomalieSonore::ListeAnomalieSonore(unsigned int nb_record, bool debug, QObject *parent) :
    QObject(parent)
    ,m_nb_record(nb_record)
    ,m_debug(debug){
}



void ListeAnomalieSonore::append_custom(AnomalieSonore &as){
    this->m_vector.append(as);
    if(this->m_vector.size() == this->m_nb_record){
        this->prepare_json();
        this->m_vector.clear();
    }
}

void ListeAnomalieSonore::prepare_json(){

    QJsonObject as_obj;
    QJsonArray as_array;

    for(int i=0; i<this->m_vector.size() ; i++){
        QJsonObject as_json;
        as_json.insert("niveau", this->m_vector.at(i).get_niveau());
        as_json.insert("date_debut", this->m_vector.at(i).get_date_debut().toString(QString("dd/MM/yyyy HH:mm:ss")));
        as_json.insert("date_fin", this->m_vector.at(i).get_date_fin().toString(QString("dd/MM/yyyy HH:mm:ss")));
        as_array.push_back(as_json);
    }
    as_obj.insert("anomalie_sonore", QJsonValue(as_array));
    QJsonDocument json_doc(as_obj);
    emit as_json_ready(json_doc);

}

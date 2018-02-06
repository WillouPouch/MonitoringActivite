#include "listeactivite.h"

ListeActivite::ListeActivite(unsigned int nb_record, QObject *parent, bool debug) :
    QObject(parent)
    ,m_nb_record(nb_record)
    ,m_debug(debug){
}



void ListeActivite::append_custom(Activite &act){
    this->m_vector.append(act);
    if(this->m_vector.size() == this->m_nb_record){
        this->prepare_json();
        this->m_vector.clear();
    }
}

void ListeActivite::prepare_json(){

    QJsonObject act_obj;
    QJsonArray act_array;

    for(int i=0; i<this->m_vector.size() ; i++){
        QJsonObject act_json;
        act_json.insert("date_debut", this->m_vector.at(i).get_date_debut().toString(QString("dd/MM/yyyy HH:mm:ss")));
        act_json.insert("date_fin", this->m_vector.at(i).get_date_fin().toString(QString("dd/MM/yyyy HH:mm:ss")));
        act_json.insert("type_activite", this->m_vector.at(i).get_type_activite());
        act_array.push_back(act_json);
    }
    act_obj.insert("activite", QJsonValue(act_array));
    QJsonDocument json_doc(act_obj);
    if(m_debug) qDebug() << "[ListEnregContinu::prepare_json] json_doc : " << json_doc.toJson(QJsonDocument::Compact);
    emit act_json_ready(json_doc);
}




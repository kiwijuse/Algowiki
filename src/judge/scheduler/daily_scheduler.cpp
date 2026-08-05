#include <bits/stdc++.h>
#include "/usr/include/mysql/mysql.h"
using namespace std;

void daily_quest_init(MYSQL * conn)
{
	char query[1001]{};
	sprintf(query, "update progress set user_prog = 0, quest_rec_rewards = 0, quest_sort_weight = 0 where quest_class = 1");
	mysql_real_query(conn, query, strlen(query));

	sprintf(query, "update progress SET sub_content = (SELECT tag_name FROM tag ORDER BY RAND() LIMIT 1) WHERE quest_id = 23");
	mysql_real_query(conn, query, strlen(query));
}

void get_user_name(MYSQL * conn, vector <string>& vec)
{
	char query[1001]{};
	sprintf(query, "select user_id from users");
	mysql_real_query(conn, query, strlen(query));

	MYSQL_RES * result = mysql_store_result(conn);
	if(result == NULL) return;

	MYSQL_ROW row = NULL;
	while((row = mysql_fetch_row(result)) != NULL)
		vec.push_back(row[0] ? row[0] : "");

	mysql_free_result(result);
}

void random_problem_gen(MYSQL * conn, string name, int quest_id, int diff)
{
	char query[1001]{};
	sprintf(query, "select problem_id from problem where difficulty in(%d, %d) and defunct = 'N' and problem_id not in ( select problem_id from accept where user_id = '%s' )", diff, diff + 1, name.c_str());
	mysql_real_query(conn, query, strlen(query));

	MYSQL_RES * result = mysql_store_result(conn);
	if(result == NULL) return;

	vector <int> problem_list;
	MYSQL_ROW row = NULL;
	while((row = mysql_fetch_row(result)) != NULL)
		problem_list.push_back(row[0] ? atoi(row[0]) : 0);

	if(problem_list.size() == 0)
	{
		sprintf(query, "update progress set sub_content = 0 where quest_id = %d and user_id = '%s'", quest_id, name.c_str());
		mysql_real_query(conn, query, strlen(query));
		return;
	}

	std::random_device rd;
	std::mt19937 gen(rd());
	std::uniform_int_distribution<int> generator(0, (int)problem_list.size() - 1);

	int problem_id = generator(gen);
	sprintf(query, "update progress set sub_content = '%d' where quest_id = %d and user_id = '%s'", problem_list[problem_id], quest_id, name.c_str());
	mysql_real_query(conn, query, strlen(query));

	mysql_free_result(result);
}
int main()
{
	//db info
	const char * server = "localhost";
   	const char * user = "hustoj";
   	const char * password = "CHANGE_ME";
	const char * database = "jol";

	//db connect
	MYSQL * conn = mysql_init(NULL);
	if (mysql_real_connect(conn, server, user, password, database, 0, NULL, 0) == NULL)
	{
        	fprintf(stderr, "mysql_real_connect() 실패: %s\n", mysql_error(conn));
        	mysql_close(conn);
        	return 1;
    	}

	//daily_quest initialization
	daily_quest_init(conn);

	//get user_names
	vector <string> user_names;
	get_user_name(conn, user_names);

	//random_problem_gen
	for(auto& str : user_names)
	{
		int quest_id(64);
		for(int diff : { 3, 5, 7 })
			random_problem_gen(conn, str, quest_id++, diff);
	}

	mysql_close(conn);
}